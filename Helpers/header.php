<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="script-src 'self'; connect-src 'self'" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Raleway&display=swap" rel="stylesheet">

    <title>bi0s</title>
    <style>
        body {
            background-color: #e7d1c9;
            padding: 0;
            margin: 0;
        }

        .main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .error {
            background-color: #d0b49f;
            width: 280px;
            height: 30px;
            border-radius: 5px;
            text-align: center;
            padding-top: 8px;
            font-size: 18px;
        }

        .upload {
            background-color: #d0b49f;
            width: 280px;
            height: 30px;
            border-radius: 5px;
            text-align: center;
            padding-top: 8px;
            font-size: 18px;
        }
        
        .form_css {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #f1e7dd;
            width: 500px;
            box-shadow: 10px 5px 5px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            gap: 25px;
            padding-bottom: 20px;
            padding-top: 20px;
            margin-bottom: 15px;
        }

        .nameDiv {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .passDiv {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .btn {
            border-radius: 6px;
            padding: 0 16px;
            height: 30px;
            cursor: pointer;
            background-color: white;
            border: 1px solid rgba(0, 0, 0, 0.16);
            box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.05);
            margin-right: 16px;
            transition: background-color 200ms;
            margin-bottom: 10px;
        }

        .btn:hover {
            transition: all 0.5s;
            background-color: #f3e7e4;
        }

        .inp {
            border-radius: 5px;
            height: 40px;
            width: 300px;
            border-style: none;
            font-size: 15px;
            padding-left: 5px;
        }

        .inp:focus {
            box-shadow: 10px 5px 5px rgba(0, 0, 0, 0.10);
            outline: none;
        }

        .inp:hover {
            background-color: #f6f2f0;
            transition: all 0.5s;
        }

        .register {
            color: black;
        }

        .regLink {
            text-decoration: none;
            color: white;
            border-radius: 5px;
        }

        input::file-selector-button:hover {
            transition: all 0.5s;
            background-color: #f3e7e4;
        }

        input::file-selector-button {
            cursor: pointer;
            background-color: white;
            font-family: raleway;
            width: 125px;
            height: 30px;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.05);
            padding-top: 2.5px;
            border: 1px solid rgba(0, 0, 0, 0.16);
            padding: 2.5px;
            margin-left: 25px;
        }

        input[type="file"] {
            cursor: pointer;
        }

        .showNote {
            background-color: white;
            overflow-x: auto;
            padding: 15px;
            border-radius: 5px;
            width: 400px;
        }   

        .viewNotes {
            background-color: white;
            width: 60vw;
            min-height: 95vh;
            display: flex;
            padding-left: 20px;
            flex-direction: column;
            margin-bottom: 10px;
            border-radius: 8px;
            font-size: 18px;
        }

        .viewNotes p {
            margin: 50px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .nav {
        height: 75px;
        background-color: #f3e7e4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        }

        .logo {
            height: 55px;
            padding-left: 10px;
        }

        .siteName {
            color: black;
            font-size: 25px;
            text-decoration: none;
            cursor: pointer;
            align-items: center;
            justify-content: space-between;
            font-family: raleway;
        }

        .choose {
            display: flex;
            align-items: center;
            padding-right: 10px;
            gap: 15px;
        }

        .choose a {
            text-decoration: none;
            padding: 7.5px;
            border: 1px solid rgba(0, 0, 0, 0.16);
            box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.05);
            border-radius: 5px;
            color: black;
            font-size: 19px;
            font-family: Montserrat;
            background-color: #e7d1c9;
        }

        .select {
            text-decoration: none;
            padding: 7.5px;
            border: 1px solid rgba(0, 0, 0, 0.16);
            box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.05);
            border-radius: 5px;
            color: black;
            font-size: 19px;
            font-family: Montserrat;
            background-color: #e7d1c9;
        }

        .select:hover {
            transition: all 0.5s;
            background-color: #f1e7dd;
        }

        .site {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .noteBody {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .viewAll {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .heading {
            background-color: #f6f2f0;
            min-width: 20vw;
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            border-radius: 6px;
            font-family: Montserrat;
            letter-spacing: 1px;
        }

        .profile {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
        }

        .img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            
        }

        .head {
            font-family: Montserrat;
            font-size: 36px;
        }

        .para {
            font-family: raleway;
        }
        
    </style>
</head>