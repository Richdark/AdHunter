<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserModelHelper
{
	/**
	 * Logged user ID
	*/
	public $id = NULL;

	/**
	 * Logged user email
	*/
	public $email = NULL;

	/**
	 * Logged user first name
	*/
	public $name = NULL;

	/**
	 * Logged user last name
	*/
	public $surname = NULL;

        /**
         *
         * Number of logged users catched billboards
         */
        public $billboards = NULL;
	
        /**
	 * Type of "device" user is logged from
	 *
	 * Can be:
	 * - 'm' for mobile
	 * - 'w' for web
	*/
	public $device_type = 'w';

	/**
	 * Says whether user is logged in or not
	*/
	public $logged = false;

	/**
	 * Class constructor
	*/
	function __construct()
	{
		//
	}
        
}
