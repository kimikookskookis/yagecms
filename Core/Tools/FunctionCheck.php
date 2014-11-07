<?php
	namespace YageCMS\Core\Tools;
	
	use \YageCMS\Core\Exception\NoPHPDocCommentFoundForMethodOrFunctionException,
	    \YageCMS\Core\Exception\MethodDoesNotExistException,
	    \YageCMS\Core\Exception\ValueCannotBeNullException,
	    \YageCMS\Core\Exception\NoReturnTypeDeclaredException;
	
	/**
	 * Some methods to validate function input and output
	 * 
	 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
	 * @version 1.0
	 * @since 1.0
	 */
	class FunctionCheck
	{
		/**
		 * Checks if the method parameters are valid
		 * 
		 * <strong>How to use:</strong>
		 * 
		 * The first line of a method should read:
		 * <code>FunctionCheck::CheckMethodParameters(__METHOD__, func_get_args());</code>
		 * That's all!
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 *
		 * @param string $method The name of the method, in the format Class::Method (<code>__METHOD__</code>)
		 * @param array $parameters The parameters with which the method was called (<code>func_get_args()</code>)
		 *
		 * @throws Exception If one single parameter was invalid, an exception will be thrown
		 *
		 * @return boolean
		 */
		public static function CheckMethodParameters($method, $parameters)
		{
			// The parameter $method needs to be split by :: to read the class name and method name
			$method = explode('::',$method);
			$class = $method[0];
			$method = $method[1];
			
			if(!method_exists($class, $method))
			{
				throw new MethodDoesNotExistException();
			}
			
			// ReflectionMethod allows us to read the PHPDoc Comment
			$refMethod = new \ReflectionMethod($class, $method);
			
			return self::CheckParameters($refMethod, $parameters);
		}

		/**
		 * Checks if the method parameters are valid
		 * 
		 * <strong>How to use:</strong>
		 * 
		 * The first line of a method should read:
		 * <code>FunctionCheck::CheckFunctionParameters(__FUNCTION__, func_get_args());</code>
		 * That's all!
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 *
		 * @var string $method The name of the function (<code>__FUNCTION__</code>)
		 * @var array $parameters The parameters with which the method was called (<code>func_get_args()</code>)
		 *
		 * @throws Exception If one single parameter was invalid, an exception will be thrown
		 *
		 * @return boolean
		 */
		public static function CheckFunctionParameters($function, $parameters)
		{
			if(!function_exists($function))
			{
				throw new FunctionDoesNotExistException($function);
			}
			
			// ReflectionMethod allows us to read the PHPDoc Comment
			$refFunction = new \ReflectionFunction($function);
			
			return self::CheckParameters($refFunction, $parameters);
		}
		
		/**
		 * Checks if the method parameters of a ReflectionMethod/ReflectionFunction are valid.
		 * This method is not meant to be called directly, it only processes the data
		 * for CheckMethodParameters() and CheckFunctionParameters()
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 *
		 * @var ReflectionMethod/ReflectionFunction $method The method/function as reflected object
		 * @var array $parameters The parameters with which the method was called
		 *
		 * @throws Exception If one single parameter was invalid, an exception will be thrown
		 *
		 * @return boolean
		 */
		private static function CheckParameters($refMethod, $parameters)
		{
			$method = $refMethod->getName();
			
			if($refMethod instanceof \ReflectionMethod)
			{
				$method = $refMethod->getDeclaringClass()->getName().($refMethod->isStatic() ? '::' : '->').$method;
			}
			
			// Read the PHPDoc comment (everything between /** and */ above the function call
			$doccom = $refMethod->getDocComment();
			
			if(!strlen($doccom))
			{
				$logcode = LogManager::_($method);
				throw new NoPHPDocCommentFoundForMethodOrFunctionException($logcode);
			}
			
			// Trim the PHPDoc-Comment (but make sure that the second parameter is set to 'true', else you get some nasty memory problem
			$doccom = StringTools::FullTrim(substr($doccom,3,-2), true);
			
			// Get every @param :types $:name inside the PHPDoc
			$vars = array();			  #@param type/othertype       $name
			$vars_found = preg_match_all('{@param ([a-zA-Z0-9\_/]+) $([a-zA-Z0-9_]+)}miu',$doccom, $vars, PREG_SET_ORDER);
											# / = Multiple Types, \ = Namespaces
			
			foreach($vars as $paramNum => $info)
			{
				// This is the type and name of the parameter as of PHPDoc
				$allowedType = $info[1];
				$name = $info[2]; // The name is only required for the exception
				
				// Convert &lt; and &gt; to < and > (because it's required for many IDEs to convert them, but we don't like them this way
				$allowedType = str_replace(array('&lt;','&gt;'), array('<','>'), $allowedType);
				
				$originalAllowedTypes = $allowedType;
				
				/*
				 * Check if the parameter allows for multiple types
				 * 		(e.g. User/int/null, which means: the parameter can be an object of the Type 'User',
				 *       the ID of a user in the database (int), or it can be omitted (null)
				 */
				if(strpos($allowedType,'/'))
				{
					$allowedTypes = explode('/',$allowedType);
				}
				else
				{
					$allowedTypes = array($allowedType);
				}
				
				// If the parameter is not given, check if NULL is allowed (which means it can be omitted)
				if(!isset($parameters[$paramNum]))
				{
					if(in_array('null',$allowedTypes))
					{
						continue;
					}
					else
					{
						throw new MandatoryParameterIsNotDefinedException(array($method, $originalAllowedTypes, $name, $paramNum));
					}
				}
				
				// Remove 'null' as an allowed type (third parameter same as above with FullTrim)
				ArrayTools::RemoveValue($allowedTypes, 'null', true);
				
				// This represents the actual value of the parameter
				$value = null;
				$realType = 'null';
				
				$value = $parameters[$paramNum];
				$realType = gettype($value);
				
				$isLastAllowedType = false;
				
				foreach($allowedTypes as $typeNum => $allowedType)
				{
					$isLastAllowedType = ($typeNum+1 == count($allowedTypes) ? true : false);
					
					if(in_array($allowedType,array('integer','string','boolean','float')))
					{
						if($realType <> $allowedType)
						{
							throw new ParameterTypeMismatchException(array($method, $originalAllowedTypes, $name, $paramNum, $realType));
						}
						else
						{
							break;
						}
					}	// Though array<type> and array<keyType, valueType> are possible, this check isn't implemented yet.
					else if(substr($allowedType,0,5) == 'array')
					{
						if(!is_array($value))
						{
							throw new ParameterTypeMismatchNotAnArrayException(array($method, $originalAllowedTypes, $name, $paramNum, $realType));
						}
						else
						{
							break;
						}
					}
					else if(!($value instanceof $allowedType) && $isLastAllowedType)
					{
						throw new ParameterTypeMismatchWrongInstanceTypeException(array($method, $originalAllowedTypes, $name, $paramNum, $realType));
					}
				}
			}
			
			return true;
		}
		
		/**
		 * Checks if the value return by a method is valid
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 *
		 * @param ReflectionMethod/ReflectionFunction $method The method/function as reflected object
		 * @param mixed $value The value returned by the method
		 *
		 * @return boolean
		 */
		public static function CheckMethodReturnValue($method, $value)
		{
			// The parameter $method needs to be split by :: to read the class name and method name
			$method = explode('::',$method);
			$class = $method[0];
			$method = $method[1];
			
			if(!method_exists($class, $method))
			{
				$logcode = LogManager::_($class."::".$method);
				throw new MethodDoesNotExistException($logcode);
			}
			
			// ReflectionMethod allows us to read the PHPDoc Comment
			$refMethod = new \ReflectionMethod($class, $method);
		
			return self::CheckReturnValue($refMethod, $value);
		}
		
		/**
		 * Checks if the value return by a function is valid
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 *
		 * @param ReflectionMethod/ReflectionFunction $method The method/function as reflected object
		 * @param mixed $value The value returned by the method
		 *
		 * @return boolean
		 */
		public static function CheckFunctionReturnValue($function, $value)
		{
			if(!function_exists($function))
			{
				throw new FunctionDoesNotExistException($function);
			}
		
			// ReflectionMethod allows us to read the PHPDoc Comment
			$refFunction = new \ReflectionFunction($function);
		
			return self::CheckReturnValue($refFunction, $value);
		}
		
		/**
		 * Checks if the value return by a method or function is valid
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 *
		 * @param ReflectionMethod/ReflectionFunction $method The method/function as reflected object
		 * @param mixed $value The value returned by the method
		 *
		 * @return boolean
		 */
		private static function CheckReturnValue($refMethod, $value)
		{
			$method = $refMethod->getName();
				
			if($refMethod instanceof \ReflectionMethod)
			{
				$method = $refMethod->getDeclaringClass()->getName().($refMethod->isStatic() ? '::' : '->').$method;
			}
				
			// Read the PHPDoc comment (everything between /** and */ above the function call
			$doccom = $refMethod->getDocComment();
				
			if(!strlen($doccom))
			{
				$logcode = LogManager::_($method);
				throw new NoPHPDocCommentFoundForMethodOrFunctionException($logcode);
			}
				
			// Trim the PHPDoc-Comment (but make sure that the second parameter is set to 'true', else you get some nasty memory problem
			$doccom = StringTools::FullTrim(substr($doccom,3,-2), true);
			
			// Get @return type
			$result = array();
			$has_return = preg_match('{@return ([a-zA-Z0-9\\\_/]+)}miu',$doccom, $result);
			# / = Multiple Types, \ = Namespaces

			if(!$has_return)
			{
				$logcode = LogManager::_("No return type declared for '".$method."'");
				throw new NoReturnTypeDeclaredException($logcode);
			}
			
			$type = $result[1];
			
			try
			{
				return self::CheckValueAgainstType($value, $type);
			}
			catch(ValueCannotBeNullException $e)
			{
				$logcode = LogManager::_("Value cannot be null for method/function '".$method."', only '".$type."' allowed");
				throw new ValueCannotBeNullException($logcode);
			}
		}
		
		public static function CheckValueAgainstType($value, $type)
		{
			// This is the type and name of the parameter as of PHPDoc
			$allowedType = $type;
			
			// Convert &lt; and &gt; to < and > (because it's required for many IDEs to convert them, but we don't like them this way
			$allowedType = str_replace(array('&lt;','&gt;'), array('<','>'), $allowedType);
		
			$originalAllowedTypes = $allowedType;
		
			/*
			 * Check if the parameter allows for multiple types
			 * 		(e.g. User/int/null, which means: the parameter can be an object of the Type 'User',
			 *       the ID of a user in the database (int), or it can be omitted (null)
			 */
			if(strpos($allowedType,'/'))
			{
				$allowedTypes = explode('/',$allowedType);
			}
			else
			{
				$allowedTypes = array($allowedType);
			}
		
			// If the parameter is not given, check if NULL is allowed (which means it can be omitted)
			if(is_null($value))
			{
				if(in_array('null',$allowedTypes))
				{
					return true;
				}
				else
				{
					$logcode = LogManager::_("Value cannot be null, only '".$originalAllowedTypes."' allowed");
					throw new ValueCannotBeNullException($logcode);
				}
			}
		
			// Remove 'null' as an allowed type (third parameter same as above with FullTrim)
			ArrayTools::RemoveValue($allowedTypes, 'null', true);
		
			// This represents the actual value of the parameter
			$realType = gettype($value);
		
			$isLastAllowedType = false;
		
			foreach($allowedTypes as $typeNum => $allowedType)
			{
				$isLastAllowedType = ($typeNum+1 == count($allowedTypes) ? true : false);
					
				if(in_array($allowedType,array('integer','string','boolean','float')))
				{
					if($realType <> $allowedType)
					{
						throw new VariableTypeMismatchException();
					}
					else
					{
						break;
					}
				}	// Though array<type> and array<keyType, valueType> are possible, this check isn't implemented yet.
				else if(substr($allowedType,0,5) == 'array')
				{
					if(!is_array($value))
					{
						throw new VariableNotAnArrayException();
					}
					else
					{
						break;
					}
				}
				else if(!($value instanceof $allowedType) && $isLastAllowedType)
				{
					throw new VariableNotInstanceOfClassException();
				}
			}
			
			return true;
		}
	}
?>