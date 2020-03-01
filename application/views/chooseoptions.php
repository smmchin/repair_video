<!DOCTYPE html>
<html>
<head>
<title>Find Video</title>
<style>
    .button {
        background-color: #008CBA;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
    }
    
</style>

</head>
<body>
    <h2>Choose Options:</h2>
    <br>
    <button onClick="location.href='<?php echo base_url();?>Video/getinfo'" class="button">Upload Video</button>
    <button onClick="location.href='<?php echo base_url();?>Video/retrievevideo'" class="button">Find Video</button>
</body>
</html>