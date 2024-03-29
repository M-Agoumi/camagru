<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   en.lang.php                                       :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/19 17:12:42 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2022/07/25 22:42:16 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */


/**
 * this is a language dictionary for multi languages
 * this is file is the main language, or you can call it the fallback in case of any error
 */


return array(
    /**
     * start of our dictionary
     */

    /** navbar */

    'home' => 'Home',
    'news' => 'News',
    'contact' => 'Contact Us',
    'about' => 'About Us',
    'camera' => 'camera',
    'login' => 'Login',
    'register' =>'Register',
    'profile' => 'Profile',

    /** error messages[form validation][models/Model.php] */

    'RULE_REQUIRED' => 'This field is required',
    'RULE_EMAIL' => 'This field must be a valid email',
    'RULE_MIN' => 'Min length of this field is {min}',
    'RULE_MAX' => 'Max length of this field is {max}',
    'RULE_UNIQUE' => 'This {field} is used by someone else',
    'RULE_WRONG' => 'Sorry, There is no matching credentials',
	'RULE_ONE_UPPERCASE' => 'This field should contains at lest one uppercase letter',
	'RULE_ONE_LOWERCASE' => 'This field should contains at lest one lowercase letter',
	'RULE_NUMBER' => 'this field must be a number',
	'RULE_NOT_ALL_NUMBER' => 'this field can\'t be all numbers'

);
