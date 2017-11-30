<?php
class BARS {
    private $bars;

    function __construct() {
        $this->bars = array(
            //CLUBE ID
            "CI" => array( "fixed" => "P" ),

            //FUNCTION NAME
            "FN" => array( 
                "size" => 1, 
                "types" => array(
                    //ID ETIQUETA           //IMPR.ETIQ     //OBR.CLASSE    //MODELO FORM   //DESCRICAO
                    array(  "id" => "0",    "tg" => "S",    "cl" => "N",    "md" => "1",    "ds" => "0-BÁSICA/NOME" ),
                    array(  "id" => "1",    "tg" => "S",    "cl" => "S",    "md" => "3",    "ds" => "1-CAPA DA PASTA DE AVALIAÇÃO" ),
                    array(  "id" => "2",	"tg" => "S",    "cl" => "S",    "md" => "3",    "ds" => "2-CAPA DE LEITURA BÍBLICA" ),
                    array(  "id" => "A",	"tg" => "S",    "cl" => "S",    "md" => "1",    "ds" => "A-CARTÃO DE CLASSE" ),
                    array(  "id" => "B",	"tg" => "S",    "cl" => "S",    "md" => "1",    "ds" => "B-CADERNO DE ATIVIDADES" ),
                    array(  "id" => "C",	"tg" => "S",    "cl" => "S",    "md" => "2",    "ds" => "C-PASTA DE CLASSE" ),
                    array(  "id" => "D",	"tg" => "N",    "cl" => "N",                    "ds" => "D-AUTORIZAÇÃO DE SAÍDA" ),
                    array(  "id" => "E",	"tg" => "S",    "cl" => "N",    "md" => "1",    "ds" => "E-CARTÃO / ESPECIALIDADES" ),
                    array(  "id" => "F",	"tg" => "N",    "cl" => "N",                    "ds" => "F-AUTORIZAÇÃO ESPECIAL" )
                )
            ),

            //FUNCTION ID FROM PARAM
            "FI" => array( "size" => 2 ),

            //PEOPLE ID - FROM PARAM
            "NI" => array( "size" => 3 )
        );
    }

    public function getAllTags(){
        return $this->bars["FN"]["types"];
    }

    //RETORNA TODAS A LISTA DE BARCODES PARA ETIQUETAS DO TIPO ESPECIFICADO
    public function getTagsTipo($tg,$vl){
        return array_filter( $this->getAllTags(), function($e) use($tg,$vl){
            return $e[$tg] == $vl;
        });
    }

    //RETORNA O OBJETO DE ACORDO COM O ID(TIPO)
    public function getTagByID($id){
        $arr = array_filter( $this->getAllTags(), function($e) use($id){
            return $e["id"] == $id;
        });
        return $arr[0];
    }
}

class PATTERNS {

    private $virtualDir;
    private $clubeDS;
    private $bars;

    function __construct() {

        //VIRTUALDIR
        $this->virtualDir = "/desbravadores/";

        //DESCRICAO DO CLUBE
        $this->clubeDS = "Clube de Desbravadores Pioneiros - IASD Capão Redondo - 6ª Região - APS - UCB - DSA";

        //DEFINICOES DO BARCODE
        $this->bars = new BARS();
    }

    public function getBars(){
        return $this->bars;
    }

    //RETORNA VIRTUAL DIR
    public function getVD(){
        return $this->virtualDir;
    }

    //RETORNA DESCRICAO DO CLUBE
    public function getCDS(){
        return $this->clubeDS;
    }
    
}
$GLOBALS['pattern'] = new PATTERNS();

$dirImgCliente = "";
$dirImgGenerico = "img/";

$dirImagens = $GLOBALS['pattern']->getVD() . $dirImgGenerico . $dirImgCliente;
$dirImgAppl = $dirImgGenerico . $dirImgCliente;
?>