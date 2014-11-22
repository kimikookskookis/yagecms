<?php
	namespace YageCMS\Core\Exception;
	
	class BaseException extends \Exception
	{
		
	}
	
	/*
	 * Class Loader
	 */
	
	class ClassNotFoundException extends BaseException {}
	class ClassFileNotFoundException extends BaseException {}
	
	/*
	 * Events
	 */
	 
	class EventPositioningModeInvalidException extends BaseException {}
	
	/*
	 * Database Record
	 */
	 
	class DataSetFieldNotDefinedException extends BaseException {}
	
	class SetterNotDeclaredException extends BaseException {}
	class GetterNotDeclaredException extends BaseException {}
	
	class UserNotFoundException extends BaseException {}
	
	class CookieNotFoundException extends BaseException {}
	
	class NoConfigurationParametersFoundByScopevalueException extends BaseException {}
	
	class NoTemplateFoundInExpectedLocationException extends BaseException {}
	
	/*
	 * Function Check
	 */
	
	class NoPHPDocCommentFoundForMethodOrFunctionException extends BaseException {}
	class MethodDoesNotExistException extends BaseException {}
	class ValueCannotBeNullException extends BaseException {}
	class NoReturnTypeDeclaredException extends BaseException {}
	class VariableTypeMismatchException extends BaseException {}
	
	/*
	 * Website Object
	 */
	
	class WebsiteNotFoundException extends BaseException {}
	
	/*
	 * URI Handlers
	 */
	
	class NoURIHandlersFoundException extends BaseException {}
	class NoURIHandlerFoundForPatternException extends BaseException {}
	
	/*
	 * Setup
	 */
	
	class SetupNotFoundException extends BaseException {}
	
	/*
	 * Module
	 */
	
	class ModuleNotFoundException extends BaseException {}
?>