<?php
json_last_error();

class JsonErrors{
   function __construct(){

      if(JSON_ERROR_DEPTH){
         print '{  "erro": "caracteres excedido. Máximo 512" }';
         http_response_code(400);
         exit();
      }

      if(JSON_ERROR_STATE_MISMATCH){
         print '{  "erro": "state mismatch" }';
         http_response_code(400);
         exit();
      }

      if(JSON_ERROR_CTRL_CHAR){
         print '{  "erro": "Caracter de controle encontrado" }';
         http_response_code(400);
         exit();
      }

      if(JSON_ERROR_SYNTAX){
         print '{  "erro": "String JSON inválida!" }';
         http_response_code(400);
         exit();
      }

      if(JSON_ERROR_UTF8){
         print '{  "erro": "Erro na codificação UTF-8" }';
         http_response_code(400);
         exit();
      }
   }
}

