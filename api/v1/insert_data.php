<?php
error_reporting(0);
date_default_timezone_set('Asia/Jakarta');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: POST");
require 'koneksi.php';

$contentType = explode(';', $_SERVER['CONTENT_TYPE']);
$accessBearerToken = $_SERVER['HTTP_AUTHORIZATION'];
$rawBody = file_get_contents("php://input");
$data = array();

if(in_array('application/json', $contentType))
{
    $data = json_decode($rawBody);
    $progress = $data->progress;
    $tanggal = $data->tanggal;
    $kode_pkl = $data->kode_pkl;
    $persen = $data->persen;
    
    $nih = substr($accessBearerToken, 6);
    $substrBasic = substr($accessBearerToken, 0, 5);

    if($substrBasic != "Basic")
    {
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array(
                'statusCode' => 401,
                'status'=> false,
                'Code'=>'01',
                'message' => 'Authentication token is required and has failed or has not yet been provided'
                ));
        exit();
    }
    
    if($nih != "N2Q5NmY2OTc5MDMxOWNmNmM1ZmViMjU4NDllYjQ0ODU6MGFhYmZkYjEwN2EwYTBjYmI0YTVlYTk3MjQyOTZjZGM=")
    {
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array(
                'statusCode' => 401,
                'status'=> false,
                'Code'=>'01',
                'message' => 'Authentication token is required and has failed or has not yet been provided'
                ));
        exit();
    }
    
    if($progress == "" || $tanggal == "" || $kode_pkl == "" || $persen == "")
    {
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array(
                    'statusCode' => 200,
                    'status'=> false,
                    'Code'=>'01',
                    'message' => 'Data tidak lengkap!'
                    ));
        exit();
    }
    
    $cekData = $conn->prepare("SELECT * FROM pkl WHERE BINARY kode_pkl=:kode_pkl ORDER BY id DESC LIMIT 1");
    $cekData->bindParam(':kode_pkl', $kode_pkl);
    $cekData->execute();
    
    if($cekData->rowCount() <= 0)
    {
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array(
                    'statusCode' => 200,
                    'status'=> false,
                    'Code'=>'01',
                    'message' => 'Kode PKL tidak ditemukan!'
                    ));
        exit();
    }
    
    $fixedPersen = $persen . "%";
    
    $insertDB = $conn->prepare("INSERT INTO mahasiswa_pkl(progress, tanggal, kode_pkl, persen) VALUES(:progress, :tanggal, :kode_pkl, :persen)");
    $insertDB->bindParam(':progress', $progress);
    $insertDB->bindParam(':tanggal', $tanggal);
    $insertDB->bindParam(':kode_pkl', $kode_pkl);
    $insertDB->bindParam(':persen', $fixedPersen);
    $insertDB->execute();
    
    if($insertDB)
    {
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array(
                    'statusCode' => 200,
                    'status'=> true,
                    'Code'=>'00',
                    'message' => 'Input data sukses!'
                    ));
        exit();
    }
    
    else
    {
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array(
                    'statusCode' => 200,
                    'status'=> false,
                    'Code'=>'01',
                    'message' => 'Input data gagal!'
                    ));
        exit();
    }
}

else
{
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array(
                'statusCode' => 404,
                'status'=> false,
                'Code'=>'01',
                'message' => 'The resource could not be found'
                ));
    exit();
}