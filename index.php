<?php
error_reporting(0);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no" />
<meta name="robots" content="index, follow">
<meta name="author" content="Wira Dwi Susanto">
<title>Input Data Progress PKL GAME GEAR RACING</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
<center><h3>INPUT DATA PROGRESS PKL GAME GEAR RACING</h3><span class="badge badge-danger">WIRA DWI SUSANTO - 17.01.53.0053</span><p style="margin-top: 10px;">Pemanfaatan Teknologi Wifi Connection dan Sensor Gyroscope untuk Controller Game Racing Menggunakan Smartphone Android</p></center>
<form>
<div class="form-group">
<label for="progress">Progress:</label>
<input type="text" class="form-control" placeholder="Progress..." name="progress" id="progress">
</div>
<div class="form-group">
<label for="tanggal">Tanggal:</label>
<input type="date" class="form-control" placeholder="Tanggal..." name="tanggal" id="tanggal">
</div>
<div class="form-group">
<label for="kode_pkl">Kode PKL:</label>
<input type="text" class="form-control" placeholder="Kode PKL..." name="kode_pkl" id="kode_pkl">
</div>
<div class="form-group">
<label for="persen">Persen:</label>
<input type="number" class="form-control" placeholder="Persen..." name="persen" id="persen">
</div>
<button type="button" name="submitData" id="submitData" class="btn btn-primary"><i class="fa fa-sign-in" aria-hidden="true"></i> Submit Data</button>
</form>
<div name="infoPKLGagal" id="infoPKLGagal" style="color: red; font-weight: bold; text-align: justify; display: none;"></div><br>
<p id="hasil"></p>
<div id="show_data"></div>
<!--<div id="resultinfopkl">
<div class="row justify-content-center" style="margin-top: -10px;">
<div class="col-12 mt-4">
<div class="alert alert-success alert-dismissible fade show" style="text-align: justify; font-size: 12px;" role="alert">
<strong></strong>
<br>
Progress: <font id="hasil_progress"></font><br>
Tanggal: <font id="hasil_tanggal"></font><br>
Kode PKL: <font id="hasil_kode_pkl"></font><br>
Persen: <font id="hasil_persen"></font><br>
</div>
</div>
</div>
</div>
</div>-->
<script>
$(document).ready(function(){
$('#infoPKLGagal').hide();
//$('#resultinfo').hide();
$.ajax({
        type:'GET',
        url: "https://api.kmsp-store.com/webservice_wiradwis/v1/get_data",
        data:{},
        dataType:'JSON',
        error:function(xhr, ajaxOptions, thrownError){
        $('#infoPKLGagal').show();},
        cache:false,
        beforeSend:function(request){
        request.setRequestHeader("Authorization", "Basic N2Q5NmY2OTc5MDMxOWNmNmM1ZmViMjU4NDllYjQ0ODU6MGFhYmZkYjEwN2EwYTBjYmI0YTVlYTk3MjQyOTZjZGM=");
        $('#resultinfopkl').show();
        },
        success:function(s){
        $('#resultinfopkl').show();
        if(s['Code'] == "01")
        {
            //
        }

        if(s['Code'] == "00")
        {
            $('#resultinfopkl').show();
            
            for(var a = 0; a < s['data'][0]['progress_data'].length; a++)
            {
                $('#show_data').append('<div id="resultinfopkl"><div class=" justify-content-center" style="margin-top: -10px;"><div class="mt-4"><div class="alert-success  pl-3 pr-3 pt-1 pb-1" style="text-align: justify; font-size: 12px;border-radius:6px" role="alert"><strong></strong><br>Progress: <font id="hasil_progress">' + s['data'][0]['progress_data'][a]['progress'] + '</font><br>Tanggal: <font id="hasil_tanggal">' + s['data'][0]['progress_data'][a]['tanggal'] + '</font><br>Kode PKL: <font id="hasil_kode_pkl">' + s['data'][0]['progress_data'][a]['kode_pkl'] +'</font><br>Persen: <font id="hasil_persen">' + s['data'][0]['progress_data'][a]['persen'] + ' </font><center><div class="progress"><div class="progress-bar progress-bar-striped" role="progressbar" style="width: ' + s['data'][0]['progress_data'][a]['persen'] + '" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div></div></center><br></div></div></div></div></div>');
            }
        }
        }});
        
// INSERT DATA
$('#submitData').click(function(){
var progress = $('#progress').val();
var tanggal = $('#tanggal').val();
var kode_pkl = $('#kode_pkl').val();
var persen = $('#persen').val();

if(progress == "" || tanggal == "" || kode_pkl == "" || persen == "")
{
    alert("Harap lengkapi dulu bagian kolom yang ada!");
    return false;
}

if(progress != "" && tanggal != "" && kode_pkl != "" && persen != "")
{
$.ajax({
        type:'POST',
        url: "https://api.kmsp-store.com/webservice_wiradwis/v1/insert_data",
        'data':JSON.stringify({"progress":progress, "tanggal":tanggal, "kode_pkl":kode_pkl, "persen":persen}),
        dataType:'JSON',
        'contentType': 'application/json',
        error:function(xhr, ajaxOptions, thrownError){
        $('#infoPKLGagal').show();
        $('#infoPKLGagal').html();
        },
        cache:false,
        beforeSend:function(request){
        request.setRequestHeader("Authorization", "Basic N2Q5NmY2OTc5MDMxOWNmNmM1ZmViMjU4NDllYjQ0ODU6MGFhYmZkYjEwN2EwYTBjYmI0YTVlYTk3MjQyOTZjZGM=");
        $('#hasil').html('Sedang menginput data. Silakan tunggu...');
        },
        success:function(s){
        $('#hasil').html(s['message']);
        if(s['Code'] == "00")
        {
            window.location.reload();
        }
        }});
}
});
});
</script>
</body>
</html>