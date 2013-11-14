<?php
/**
 * Copyright (c) 2013 Jürgen Mackiol
 *
 * http://www.mackiol.net
 *
 * Contributors:
 * Jürgen Mackiol - initial contents
 *
 * Webmailer script with server side validation (incl. custom captcha from captcha.php)
 */

session_start();

if (isset($_SESSION['captcha']) && isset($_POST['operation'])) {
    // generates validation output based on given operation in post data
    // return has JSON format
    switch ($_POST['operation']) {
        case 'notification':
        {
            // no validation; only clean data notification
            $notification = null;
            if (isset($_SESSION['notification'])) {
                $notification = $_SESSION['notification'];
                unset($_SESSION['notification']);
            } else {
                $notification = '{"status": "new"}';
            }
            echo $notification;
            break;
        }
        case 'submit':
        {
            // validates every field; checks only for bad data; correct data is assumed (shorter code)
            $resultArray = array();
            $resultArray['status'] = 'success';
            $resultArray['formData'] = array();

            $nameArray = array();
            if (trim($_POST['name']) === '') {
                $nameArray['data'] = '';
                $nameArray['validation'] = 'Name is required!';
                $resultArray['status'] = 'error';
            } else {
                $name = trim($_POST['name']);
                $name = strip_tags($name);
                $nameArray['data'] = $name;
                $nameArray['validation'] = '';
            }
            $resultArray['formData']['name'] = $nameArray;

            $emailArray = array();
            if (trim($_POST['email']) === '') {
                $emailArray['data'] = '';
                $emailArray['validation'] = 'E-Mail is required!';
                $resultArray['status'] = 'error';
            } elseif (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+.[A-Z]{2,4}$", trim($_POST['email']))) {
                $emailArray['data'] = '';
                $emailArray['validation'] = 'Invalid e-mail address!';
                $resultArray['status'] = 'error';
            } else {
                $email = trim($_POST['email']);
                $email = strip_tags($email);
                $emailArray['data'] = $email;
                $emailArray['validation'] = '';
            }
            $resultArray['formData']['email'] = $emailArray;

            $messageArray = array();
            if (trim($_POST['message']) === '') {
                $messageArray['data'] = '';
                $messageArray['validation'] = 'Message is required!';
                $resultArray['status'] = 'error';
            } else {
                if (function_exists('stripslashes')) {
                    $message = stripslashes(trim($_POST['message']));
                    $message = strip_tags($message);
                } else {
                    $message = trim($_POST['message']);
                    $message = strip_tags($message);
                }
                $messageArray['data'] = $message;
                $messageArray['validation'] = '';
            }
            $resultArray['formData']['message'] = $messageArray;

            $captchaArray = array();
            if (trim($_POST['captcha']) === '') {
                $captchaArray['data'] = '';
                $captchaArray['validation'] = 'Verification code is required!';
                $resultArray['status'] = 'error';
            } else {
                if (md5(trim($_POST['captcha'])) != $_SESSION['captcha']) {
                    $captchaArray['data'] = '';
                    $captchaArray['validation'] = 'Verification code was wrong!';
                    $resultArray['status'] = 'error';
                } else {
                    $captchaArray['data'] = '';
                    $captchaArray['validation'] = 'Verification code has changed!';
                }
            }
            $resultArray['formData']['captcha'] = $captchaArray;

            if ($resultArray['status'] == 'success') {
                // if no error was found in the form data - send the mail;
                $emailTo = "recipient@domain.tld";
                $subject = '[domain.tld] message from ' . $name;
                // body format can be changed to taste
                $body = "Name : $name \nE-Mail : $email \n\nMessage :\n$message";
                $headers = 'From: ' . $name . ' <' . $email . '>' . "\n" . 'Reply-To: ' . $email;
                try {
                    mail($emailTo, $subject, $body, $headers);
                    $resultArray['notification'] = 'Your message has been sent!';
                } catch (Exception $e) {
                    $resultArray['notification'] = 'Your message could not been sent. Please try again later!';
                    $resultArray['status'] = 'error';
                }
            } else {
                $resultArray['notification'] = 'Please fill out the missing or wrong form fields and submit the message again!';
            }
            $notification = json_encode($resultArray);
            $_SESSION['notification'] = $notification;
            unset($_SESSION['captcha']);
            // redirect back to the form - important to show validation result in template (AJAX)
            header('Location: http://domain.tld/contact/#');
            break;
        }
        default:
            {
            echo '';
            }
    }
}
?>
