<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Ready for Pick up</title>
</head>
<style>
    .trife-email-template-container{
        width: 60%;
        margin: 20px auto;
        border:1px solid #d1d1d1;
    }

    .trife-email-header{
        background-color: #085fe0;
        padding: 20px 10px;
        text-align: center;
        color: white;
    }
    .trife-email-body{
        padding: 15px;
        width: 100%;
    }
    .trife-table-body{
        width: 100%;
    }
    tr{
        padding: 10px;
        width: 100%;
        text-align: left;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    th{
        width:max-content;
    }
</style>
<body>
    <div style=" width: 60%;
        margin: 20px auto;
        border:1px solid #d1d1d1;">
        <?php echo $html; ?>

    </div>
    

</body>
</html>