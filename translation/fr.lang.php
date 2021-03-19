<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   fr.lang.php                                       :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/19 17:49:35 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/03/19 17:49:35 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */


/**
 * this is a language dictionary for multi languages
 * this is file is the main language or you can call it the fallback in case of any error
 */


return array(
    /**
     * start of our dictionary
     */

    /** navbar */

    'home' => 'Accueil',
    'login' => 'Login',
    'register' =>'Register',

    /** error messages[form validation][models/Model.php] */

    'RULE_REQUIRED' => 'This field is required',
    'RULE_EMAIL' => 'This field must be a valid email',
    'RULE_MIN' => 'Min length of this field is {min}',
    'RULE_MAX' => 'Max length of this field is {max}',
    'RULE_UNIQUE' => 'This field is used by someone else',
);