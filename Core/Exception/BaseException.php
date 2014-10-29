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
?>