<!DOCTYPE html>
<html>

<head>

    <title>Laravel Desktop Notifier</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
        }


        .card {

            width: 450px;
            margin: 100px auto;
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.1);

        }


        h1 {

            color: #333;
            margin-bottom: 30px;

        }


        .btn {

            display: inline-block;
            padding: 15px 35px;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 18px;
            transition: .3s;

        }


        .btn:hover {

            background: #084298;

        }


        .alert {

            margin-top: 30px;
            padding: 20px;
            border-radius: 12px;
            background: #d1fae5;
            border-left: 6px solid #16a34a;
            color: #166534;
            animation: fade .5s;

        }


        .alert h3 {

            margin: 0 0 10px;

        }


        .icon {

            font-size: 40px;

        }


        @keyframes fade {

            from {

                opacity: 0;
                transform: translateY(-20px);

            }

            to {

                opacity: 1;
                transform: translateY(0);

            }

        }
    </style>

</head>


<body>


    <div class="card">


        <div class="icon">
            🔔
        </div>


        <h1>
            Laravel Desktop Notification
        </h1>



        <a href="/notify" class="btn">
            Send Desktop Notification
        </a>



        @if(session('success'))

        <div class="alert">

            <div class="icon">
                ✅
            </div>

            <h3>
                Success!
            </h3>

            <p>
                {{ session('success') }}
            </p>

        </div>

        @endif



    </div>



</body>

</html>