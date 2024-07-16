<?php
header("Content-Type: text/html; charset=UTF-8");
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include_once "../../root_path.php";//NO BORRAR
include_once "../funciones/array.inc.php";
require_once ROOT1. 'models' .DS1 .'subguiaModel.php';
require_once ROOT1. 'models' .DS1 .'ciudadModel.php';
require_once ROOT1. 'models' .DS1 .'departamentoModel.php';
require_once ROOT1. 'models' .DS1 .'mapsModel.php';
require_once ROOT1. 'models' .DS1 .'horarioModel.php';
require_once ROOT1. 'models' .DS1 .'funcHelp.php';
require_once ROOT1. 'models' .DS1 .'habitacionesModel.php';
require_once ROOT1. 'models' .DS1 .'countriesModel.php';
require_once ROOT1. 'models' .DS1 .'promocionModel.php';
require_once ROOT1. 'models' .DS1 .'catalogoModel.php';
require_once ROOT1 ."application".DS1."Session.php";
include_once '../../vendor/phpqrcode/qrgenerateimg.php';
$habModel = new habitacionesModel();
$paisModel = new countriesModel();
$deptoModel = new departamentoModel();
$ciudadModel = new ciudadModel();
$mapsModel = new mapsModel();
$horarioModel = new horarioModel();
$subguiaModel = new subguiaModel();
$promocionModel = new promocionModel();
$catalogoModel = new catalogoModel();

Session::init();
$paginaBusiness = $_GET["bc"];

$OpMesArray = array("1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre");
$OpcMes = array("1" => "Ene", "2" => "Feb", "3" => "Mar", "4" => "Abr", "5" => "May", "6" => "Jun", "7" => "Jul", "8" => "Ago", "9" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dic");
$OpDiaArray = array("1" => "Lunes", "2" => "Martes", "3" => "Miércoles", "4" => "Jueves", "5" => "Viernes", "6" => "Sábado", "7" => "Domingo");
$opHora = array("0" => "00:00", "1" => "00:30", "2" => "01:00", "3" => "01:30", "4" => "02:00", "5" => "02:30", "6" => "03:00", "7" => "03:30", "8" => "04:00", "9" => "04:30", "10" => "05:00", "11" => "05:30", "12" => "06:00", "13" => "06:30", "14" => "07:00", "15" => "07:30", "16" => "08:00", "17" => "08:30", "18" => "09:00", "19" => "09:30", "20" => "10:00", "21" => "10:30", "22" => "11:00", "23" => "11:30", "24" => "12:00", "25" => "12:30", "26" => "13:00", "27" => "13:30", "28" => "14:00", "29" => "14:30", "30" => "15:00", "31" => "15:30", "32" => "16:00", "33" => "16:30", "34" => "17:00", "35" => "17:30", "36" => "18:00", "37" => "18:30", "38" => "19:00", "39" => "19:30", "40" => "20:00", "41" => "20:30", "42" => "21:00", "43" => "21:30", "44" => "22:00", "45" => "22:30", "46" => "23:00", "47" => "23:30", "48" => "23:59");
$fechahoy = date("Y-m-d");
$fechaHora = date("Y-m-d H:i:s");

if($paginaBusiness){
    $empresa = $empresaDirectorio->getEmpresaUrl($paginaBusiness);
    if (!empty($empresa)){
        $nombre			= $empresa["descripcion"];
        $nombre1		= urlencode($nombre);
        $direccion		= $empresa["direccion"];
        $depto			= $empresa["coddepto"];
        $nombredepto = $empresa["depdesc"];
        $codigotelf = $empresa["codtelefono"];
        $ciudad			= $empresa["codciudad"];
        $nombreciudad = $empresa["cdesc"];
        $ciudadseo = $empresa["ciudadseo"];
        $telefono1		= $empresa["telefono"];
        $vtel = explode("|",$telefono1);
        $celular		= $empresa["celular"];
        $callfree = $empresa["callfree"];
        $wsapp		= $empresa["whatsapp"];
        $whatsapp		= $wsapp==""?null:explode(",",$wsapp);
//        $numeros = serialize("76891760,69111885");
        $emergencia     =$empresa["emergencia"];
        $vcel           = explode("|",$celular);
        $fax			= $empresa["fax"];
        if($empresa["web"]) $web = $empresa["web"];
        $mail			= $empresa["mail"];
        $codeMapa		= $empresa["codmapa"];
        $actividades	= $empresa["actividades"];
        $actividadesHTML = strip_tags($actividades);
        $clave			= $empresa["buscar"];
        $business 		= $empresa["businesscard"];
        $sucursales		= $empresa["codsucursal"];
        $guia		= $empresa["codguia"];
        $subguia		= $empresa["codsubguia"];///  diferencia universidades
        $sguia = $subguia==""?null:explode(" ",$subguia);
        $rubros			= $empresa["codrubro"];
        if($rubros) $rubross = explode("-",$rubros);
        $tsuscripcion	= $empresa["tiposuscripcion"];
        $paginabus		= $empresa["paginabusiness"];
        $comentario		= $empresa["comentario"];
        $keywords		= $empresa["keywords"];
        $rubroasoc     = $empresa["rubro_asoc"];
        $tipocel      = $empresa["tipo_cel"];
        $pageurl      = base_url()."/amarillas/businesscard/".$paginabus.".html";


        $demp = rand(100,999).$empresa["codempresa"];
        if(isset($_GET["ce"])){
            $codigoempresa = $_GET["ce"];
        }else{
            $codigoempresa = $empresa["codempresa"];
        }

        if(isset($keywords)){
            $keywords = $empresa["keywords"];
            $arrayKey = explode(",",$keywords);
            $arrKeyW = array();
            foreach ($arrayKey as $row){
                $arrKeyW[] = trim($row);
            }
            $arrayKey = implode(", ",$arrKeyW);
        }

        $businesscod 	= $empresa["businesscard"];
        $BisCode		= sha1($businesscod); /// CODIGO CATALOGO PDF ANTIGUO
        $bempresa 		= $empresa["bempresa"];
//        $codeEmp 		= $empresa; $codeEmp = "MAP".$codeEmp;
        $codeEmp 		= $codigoempresa;
        $codeEmp = "MAP".$codigoempresa;
        $tipo           =$empresa["tipo_business"];
        $auspiciador    =$empresa["auspiciador"];
        $auspiciador_cel  =$empresa["auspiciador_cel"];
        $nauspiciadores =$empresa["auspiciadores"];
        $productos 		= $empresa["servicios"];
        $especialidades = $empresa["especialidades"];
        $certificados   = $empresa["certificados"];
        $horarioEmpresa = $horarioModel->getHorarioEmpresa($codigoempresa);
        if (count($horarioEmpresa) > 0) {
            $dias_horario = $horarioEmpresa[0]["dias_horario"];
            $mas_horario = $horarioEmpresa[0]["mas_horario"];
            $checkin = $horarioEmpresa[0]["checkin"];
        } else {
            $horarioEmpresa = $horarioModel->getHorarioEmpresa($empresa["bempresa"]);
            if(count($horarioEmpresa) > 0){
                $dias_horario = $horarioEmpresa[0]["dias_horario"];
                $mas_horario = $horarioEmpresa[0]["mas_horario"];
                $checkin = $horarioEmpresa[0]["checkin"];
            }else{
                $horarios = $empresa["horarios"];
            }
        }
        $pagos 			= $empresa["pagos"];
        $fotos 			= $empresa["fotografias"];
        if(isset($fotos)){
            $foto = explode(";",$fotos);
            $cfotos 		= count($foto);
        }
        $video 			= $empresa["video"];
        $logo 			= $empresa["logo"];
        $catalogoFoto = $empresa["catalogo"];
        if ($catalogoFoto) $catalogo = explode(";", $catalogoFoto);
        $catalogoWeb    = $empresa["catalogo_web"];
        $pcontacto 		= $empresa["pcontacto"];
        $tarjetas 		= $empresa["tarjetas"];
        if($tarjetas) $tarjeta = explode(";",$tarjetas);
        $promos = $promocionModel->getPromociones($bempresa, $fechaHora);
        $catalogotab = $catalogoModel->getCatalogos($bempresa);

        $catalogoImg 	= $empresa["imgcatalogo"];

        $catalogo_pdf =$empresa["catalogo_pdf"];
        $catalogo_pdf_img =$empresa["catalogo_pdf_img"];

        $portafolio = $empresa["portafolio"];
        $sociales 		= $empresa["redes"];
        if($sociales) $rsociales = explode("|",$sociales);
        $codigo_qr 		= $empresa["codigo_qr"];
        $comentarios	= $empresa["comentarios"];
        $widgets		= $empresa["extra"];
        $o_serv         = $empresa["otros_servicios"];
        $comodidades    = $empresa["comodidades"];
        $mas_servicios  = $empresa["mas_servicios"];
        if($o_serv) $otros_servicios = explode("|", $o_serv);
        $chat  = $empresa["chat"];
        $tienda =$empresa["tienda"];
        $tienda_link=$empresa["tienda_link"];
        $acerca_de_mi=$empresa["acerca_de_mi"];
        $especialidad_profesional=$empresa["especialidad_profesional"];
        $institucion=$empresa["institucion"];
        $fondo_profesional=$empresa["fondo_profesional"];
        $personal=$empresa["personal"];
        $color=$empresa["color"];

        $reserva_cita_doctor = explode("|", $sociales);
        //echo $reserva_cita_doctor[11];



        $arrayGuia = array("g006"=>"restaurantes-gastronomia","g007"=>"hoteles-bolivia","g010"=>"guiamedica","g011"=>"bolivia-industrias");
        $bool_c = false;
        if(isset($_COOKIE['betmbc'])){
            $cookie = explode(",", $_COOKIE['betmbc']);
            if(!in_array($businesscod,$cookie)){
                $cookie[count($cookie)+1]= $businesscod;
                $bool_c = true;
            }
            $cookie = implode(",", $cookie);
        }else{
            $cookie = $businesscod;
            $bool_c = true;
        }
        setcookie("betmbc", $cookie, 0);

        $habitaciones = $habModel->getHabitacionesEmpresa($codigoempresa);
        $servicios_hotel = $habModel->getServiciosEmpresa($codigoempresa);
    }else{
        header("HTTP/1.0 404 Not Found");
        include ('../../404.php');
        exit();
    }
}else{
    header("HTTP/1.0 404 Not Found");
    include ('../../404.php');
    exit();
}

if(isset($dias_horario)){
    $arraydias = json_decode($dias_horario);

    $fechaactual = getdate();
    $hora_hoydia = strtotime($fechaactual['hours'].":".$fechaactual['minutes'].":".$fechaactual['seconds']);
//    $hora_hoydia = strtotime("20:31:15");
    $posi = 0;

    for($i = 0; $i < count($arraydias[date("N")]); $i++){
        $hora_ini = strtotime($opHora[$arraydias[date("N")][$i]]);
        if($hora_hoydia > $hora_ini ){
            $posi = $i;
        }
    }
    if($posi % 2 == 0){
        $estado_horario = "Cerrado ahora";
        $class_estado = "estado_cerrado";
        $tipo_icon= "fas fa-clock";
        $hcolor = "#e74c3c";
    }else{
        $estado_horario = "ABIERTO AHORA";
        $class_estado = "estado_abierto";
        $tipo_icon= "far fa-clock";
        $hcolor = "#2ecc71";
    }
}

$pclave = explode(" ",$clave);
$pclave = array_unique($pclave);

$cadenarubro = "";
for($i = 0 ; $i < 1; $i++){
    $webi1 = substr($rubross[$i],1);
    $mybusca = $rubroModel->recuperarRubro($webi1);
    if(count($mybusca)>0){
        $am_rubro = substr($mybusca[0]["codrubro"], 1);
        if($i == count($rubross)-1){
            $cadenarubro.=$mybusca[0]['descripcion'];
        }else{
            $cadenarubro.=" | ".$mybusca[0]['descripcion'];
        }
    }
}

$rubros1= implode("",$rubross);
$rubros1 = explode("r",$rubros1);
$descrubros = array();
for ($i = 0 ; $i < count($rubros1);$i++){
    $buscarubro = $rubroModel->recuperarRubro($rubros1[$i]);
    if(count($buscarubro)>0){
        $descrubros[] = $buscarubro[0]['descripcion'];
    }
}

if(isset($codeMapa)){
    $mapsearch = $mapsModel->recuperaMapa($codeMapa);
    if (count($mapsearch)>0){
        $cmapa 		= $mapsearch[0]["codmapa"];
        $cempresa	= $mapsearch[0]["codempresa"];
        $clat		= $mapsearch[0]["lat"];
        $clng		= $mapsearch[0]["lng"];
        $cmzm 		= $mapsearch[0]["zoom"];
    }
}

function starBar($numStar, $mediaId, $starWidth, $promRating, $promCantidad) {
    $nbrPixelsInDiv = $numStar * $starWidth; // Calculate the DIV width in pixel
//num of pixel to colorize (in yellow)
    $numEnlightedPX = round($nbrPixelsInDiv * $promRating / $numStar, 0);

    $getJSON = array('numStar' => $numStar, 'mediaId' => $mediaId); // We create a JSON with the number of stars and the media ID
    $getJSON = json_encode($getJSON);

    $starBar = '<div id="'.$mediaId.'" class="star_content">';
    $starBar .= '<div class="star_bar" style="display:inline-block;width:'.$nbrPixelsInDiv.'px; height:'.$starWidth.'px; background: linear-gradient(to right, #ffc600 0px,#ffc600 '.$numEnlightedPX.'px,#ccc '.$numEnlightedPX.'px,#ccc '.$nbrPixelsInDiv.'px);" rel='.$getJSON.'>';
    for ($i=1; $i<=$numStar; $i++) {
        $starBar .= '<div title="'.$i.'/'.$numStar.'" id="'.$i.'" class="star"';
        $starBar .= ' onmouseover="overStar('.$mediaId.', '.$i.', '.$numStar.'); return false;" onmouseout="outStar('.$mediaId.', '.$i.', '.$numStar.'); return false;" onclick="rateMedia('.$mediaId.', '.$i.', '.$numStar.', '.$starWidth.'); return false;"';
        $starBar .= '></div>';
    }
    $starBar .= '</div>';
    $starBar .= '<div class="resultMedia'.$mediaId.'" style="display:inline-block;margin-left:10px;margin-top:5px;vertical-align:top;font-size: small; color: grey">'; // We show the rate score and number of rates
    if ($promCantidad == 0) $starBar .= 'No hay votos';
    else $starBar .= '<span style="font-size: 14px;font-weight: bold;">'.$promRating . '</span> / <span style="font-size: 12px;">' . $numStar.'</span>';
    $starBar .= '</div>';
    $starBar .= '<div class="box'.$mediaId.'"></div>';
    $starBar .= '</div>';
    return $starBar;
}
$paises = $paisModel->getCountries();

$cont=count(explode(';', $auspiciador));
$cont_ausp=count(explode(';', $auspiciador));

//Para el Keyword
$aux="";
if(isset($rubros)){
    for($i = 0 ; $i < count($rubross); $i++){
        $webi1 = substr($rubross[$i],1);
        $mybusca = $rubroModel->recuperarRubro($webi1);
        if(count($mybusca)>0){
            $am_rubro = substr($mybusca[0]["codrubro"], 1);
            $aux=$aux.$mybusca[0]['descripcion']. ", ";
            $i+1;
        }
    }
}
$cad = substr ($aux, 0, strlen($aux) - 2);
//    echo $cad;

$nombresn=explode('*', $nombre);
//echo $nombresn[0];
$nombre_edit= str_replace('"', '', $nombre);

$sucur = $empresaDirectorio->buscarSucursal($sucursales);
$sucur1= $empresaDirectorio->buscarEmpresaBusiness($sucur[0]["codsucursal"]);

$colores="#ffff";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, minimum-scale=1">
    <title><?=trim($nombresn[0])?> | boliviaentusmanos</title>
    <meta name="title" content="<?=trim($nombresn[0])?> | boliviaentusmanos">
    <meta name="description" content="<?=$nombre?>. <?=$actividadesHTML ?>">
    <?php if($tipo==8){?>
        <meta name="keywords" content="<?=$nombre?>. Tarjeta on-line, digital, Business Card, dirección, teléfonos, tiendas, oficinas, consultorios, <?=$cad?>. <?=$clave?>">
    <?php } else {?>
        <meta name="keywords" content="<?=$nombre?>. Tarjeta on-line, digital, Business Card, dirección, teléfonos, tiendas, oficinas, consultorios, <?=$cad?>">
    <?php  } ?>
    <meta property="og:type" content="website">
    <?php if($sucursales==null){ ?>
        <meta property="og:url" content="https://www.boliviaentusmanos.com/amarillas/businesscard/<?=$paginaBusiness?>.html">
    <?php  }else{?>
        <meta property="og:url" content="https://www.boliviaentusmanos.com/amarillas/businesscard/<?=$sucur1[0]["paginabusiness"]?>.html">
    <?php  } ?>
    <meta property="og:title" content="<?=trim($nombresn[0])?> | boliviaentusmanos">
    <meta property="og:description" content="<?=$actividadesHTML ?>">
    <meta property="og:image" content="<?php echo isset($logo) ? base_url()."/amarillas/blogos/".$logo : base_url()."/amarillas/imagenes/fb-icon-amarillas.jpg"?>">
    <meta property="fb:pages" content="403676053023936">
    <meta property="fb:app_id" content="966242223397117">
    <meta name="twitter:card" content="summary_large_image">
    <?php if($sucursales==null){ ?>
        <meta name="twitter:url" content="https://www.boliviaentusmanos.com/amarillas/businesscard/<?=$paginaBusiness?>.html">
    <?php  }else{?>
        <meta name="twitter:url" content="https://www.boliviaentusmanos.com/amarillas/businesscard/<?=$sucur1[0]["paginabusiness"]?>.html">
    <?php  } ?>
    <meta name="twitter:title" content="<?=trim($nombresn[0])?> | boliviaentusmanos">
    <meta name="twitter:description" content="<?=$nombre?>. <?=$actividadesHTML ?>">
    <meta name="twitter:image" content="<?= isset($logo) ? base_url()."/amarillas/blogos/".$logo : base_url()."/amarillas/imagenes/fb-icon-amarillas.jpg"?>">
    <meta name="twitter:site" content="@boliviaetm">
    <meta name="language" content="es">
    <meta name="robots" content="index, follow"/>
    <meta name="googlebot" content="index,follow">
    <meta name="author" content="Boliviaentusmanos.com"/>
    <meta content="global" name="distribution">
    <link rel="shortcut icon" type="image/x-icon" href="https://www.boliviaentusmanos.com/favicon.ico" />
    <link rel="icon" type="image/x-icon" href="https://www.boliviaentusmanos.com/favicon.ico" />
    <?php if($guia == "amarillas"){ ?>
        <meta name="theme-color" content="#FFCB05"/>
        <meta name="msapplication-navbutton-color" content="#FFCB05"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="#FFCB05"/>
    <?php } ?>
    <?php if($guia == "g007"){ ?>
        <meta name="theme-color" content="#0C5CA3"/>
        <meta name="msapplication-navbutton-color" content="#0C5CA3"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="#0C5CA3"/>
    <?php } ?>
    <?php if($guia == "g006"){ ?>
        <meta name="theme-color" content="#7A9D00"/>
        <meta name="msapplication-navbutton-color" content="#7A9D00"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="#7A9D00"/>
    <?php } ?>


    <link rel="stylesheet" href="/css/style_menu_min.css" type="text/css" media="all">
    <?php if($tipo!=8){ ?>
    <link rel="stylesheet" href="/css/megamenu/megamenu_min.css" type="text/css" media="screen"/>
    <?php  } else{?>
        <link rel="stylesheet" href="https://www.boliviaentusmanos.com/amarillas/css/menu_horizontal.css">
    <?php } ?>
    <?php if($sucursales==null){ ?>
        <link rel="canonical" href="https://www.boliviaentusmanos.com/amarillas/businesscard/<?=$paginaBusiness?>.html"/>
   <?php  }else{?>
        <link rel="canonical" href="https://www.boliviaentusmanos.com/amarillas/businesscard/<?=$sucur1[0]["paginabusiness"]?>.html"/>
  <?php  } ?>
    <link rel="image_src:image" href="<?= isset($logo) ? base_url()."/amarillas/blogos/".$logo : base_url()."/amarillas/imagenes/fb-icon-amarillas.jpg"?>"/>
    <link rel="stylesheet" type="text/css" href="/plugins/hiraku2/css/hiraku.min.css">
    <link rel="stylesheet" href="https://www.boliviaentusmanos.com/plugins/slick/slick1_min.css">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://www.boliviaentusmanos.com/amarillas/businesscard/css/all.css">
    <style>@import url(https://fonts.googleapis.com/css?family=Fjalla+One&display=swap); @import url(https://fonts.googleapis.com/css?family=Roboto);  *{margin:0;padding:0}  body{font:normal 12px 'Roboto', Verdana, Helvetica, sans-serif;background-color:#FFF}  h5, h4, h3, h2, h1, p, li, ul{display:block}  li{ list-style-type:none}  a, a:link{text-decoration:none;color: #3d3b3b}  a:hover{ text-decoration:underline}  .clear{clear:both}  img{ border:none}
        #header{background-color:
        <?php if($guia == "g007"){
          echo "#eeeeee";} ?>
          <?php
            if($guia == "amarillas"){
          echo "#eeeeee";} ?>
        <?php
        if($guia == "g006"){
      echo "#eeeeee";} ?>
        <?php
    if($guia == "g010"){
  echo "#eeeeee";} ?>
        <?php
if($guia == "g011"){
echo "#eeeeee";} ?>
          }

        #wrapper{margin:0 auto;width:98%;max-width:1100px;font-size:12px;position:relative}  header .menu-nav{display:none;}
        <?php if($navegador=="MOBILE") { ?>
        #toggleMenu{background:#040404;display:block;width:100%;display:table;padding:5px 0}
        #toggleMenu #buscador{vertical-align:middle; padding:0 5px}
        #toggleMenu #emenu #navbar-toggle{display:block;color:#FFF;background:#000 url(/imagenes/toggleMenu.png) no-repeat center; width:40px; height:40px;font:bold 16px Arial;}
        #toggleMenu #buscador #search{background:#EEE;overflow:hidden;display:table;width:100%}
        #toggleMenu #buscador #search div{display:table-cell;vertical-align:middle}
        #toggleMenu #buscador #search div#se{width:50px;}
        #toggleMenu #buscador #search div#tx{width:auto}
        #toggleMenu #buscador input#buscar{background:#FFCB05 url(/imagenes/buscar.png) no-repeat center;width:100%;height:40px;border:none;cursor:pointer}
        #toggleMenu #buscador input#query{font:normal 14px 'Roboto', Verdana;padding:0px 10px;color:#333;width:100%;background:#FFF;border:#EEE solid 1px;height:40px;}
        header .menu-nav {display:inline-block;position:absolute;right:17px;top:14px;cursor:pointer;line-height:28px;font-size:14px;}
        header .menu-btn {display:inline-block;float:left;padding-right:8px;}
        header .menu-btn span{display:block;width:27px;height:4px;margin:4px 0;background: <?= $guia == "g007" ? "#FFF":"#000" ?>;line-height: 26px;}
        .wraper{max-width:1200px;margin:auto;padding:0 20px;position:relative;z-index: 10;}

        .content{z-index:10;color:#2a2a2a;}
        .content a{color:#2a2a2a;text-decoration:underline;}
        .content a:hover{text-decoration:none;}
        .responsive-menu{top:0;right:0;left:0;bottom:0;background:rgba(28,28,28,0.98);z-index:1000;color:#FFF;box-sizing:border-box;padding-top: 120px;padding-top: 45px; position: absolute;  bottom: auto;display: none;overflow: scroll;}
        .responsive-menu a{color:#FFF;}
        .responsive-menu .close-btn{width:25px;height:25px;position:absolute;top: -25px;right:25px;-webkit-transition-duration: 0.6s;-moz-transition-duration: 0.6s;-o-transition-duration: 0.6s;transition-duration: 0.6s;-webkit-transition-property: -webkit-transform;-moz-transition-property: -moz-transform;-o-transition-property: -o-transform;transition-property: transform;overflow: hidden;}
        .responsive-menu .close-btn img{width:100%;height:auto;position:relative;display:block;cursor:pointer;}
        .close-btn:hover{-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-o-transform:rotate(45deg);cursor: pointer;}
        .expand{display:block !important;}
        .block{width:100%;}
        .menu-column{width:100%;position:relative;display:block;float:left;box-sizing:border-box;padding-bottom:20px;}
        .menu-main{color: #FFF;}
        .menu-main a{color:#FFF;font-style:normal !important;text-decoration:none;}
        .menu-main a:hover{text-decoration:underline;}
        #menu-main-menu, .responsive-only{position:relative;width: 100%;text-align:left;}
        #menu-main-menu li a{display:block;margin:18px 0;}
        #menu-main-menu li{position:relative;border-top:dotted 1px #5f5454}
        #menu-main-menu .sub-menu {display: none;border-bottom:none;}
        #menu-main-menu .sub-menu li{border-top:solid 1px #584E4E;line-height: 20px;text-transform:uppercase;background-color:#4E4545;}
        #menu-main-menu .sub-menu li a{margin:10px;display: inline-block}
        #menu-main-menu .visible{display:block;}
        .open-menu-link{display:none;position:absolute;right:0;top:0;line-height:40px;color:#FFF;font-size:18px;cursor:pointer; background-color:#4E4545; width:40px; text-align:center; margin-top:6px; font-weight:bold}
        <?php } ?>
        header .menu-nav{display:none;} header{font-size:11px;display:block;margin:0 auto;width:98%;max-width:1100px;}header #LogoOptions #logo_betm{background-color: <?=$guia == "g020" ? "#FFF":"#000"?>;display:inline-block;float: left;padding: 33px 20px 34px 20px;width: 78px;height: 51px}header #LogoOptions #logo_img{float:left;padding:33px 20px;width:auto;}header #LogoOptions #logo_img img{display:block;width:430px;height: 49px}.fixed-nav-header{-ms-box-shadow: 0 4px 15px -5px #555;-moz-box-shadow: 0 4px 15px -5px #555;-o-box-shadow:0 4px 15px -5px #555;-webkit-box-shadow:0 4px 15px -5px #555;box-shadow: 0 4px 15px -5px #555;position: fixed !important;z-index: 200;width: 100%;}.dtable{display:table;width:100%;}  .drow{display:table-row;width:100%;}.dcell{display:table-cell;padding-bottom:10px;width: 45%;}.sidebar-offcanvas{display:none;}.js-hiraku-offcanvas-body-active .js-hiraku-offcanvas-sidebar-left{background-color:#000; }.hiraku-open-btn-line, .hiraku-open-btn-line:after, .hiraku-open-btn-line:before {background: <?=$guia == "g020" ? "#FFF":"#000"?>;}#search_content{width:100%;position:relative;background:rgba(224, 224, 224, 0.5);}#search_content #search{margin:0 auto;width:98%;max-width:1100px;padding: 10px 0;}#search_content #search input{border:1px solid #c5c5c5;border-radius:4px 0 0 4px;float:left;font-size: 15px;padding: 8px 10px;width: 45%;}  #search_content #search select{border:1px solid #c5c5c5;border-radius:0 4px 4px 0;float:left;font-size: 15px;padding: 8px 10px;margin-left:-1px;width: 45%;}  #search_content #search button{background:#FFCB05;border:0;border-radius:2px;cursor: pointer;font-weight:bold;font-size: 15px;float:right;padding: 10px 0;width: 40px;}  #search_content #search button:hover{background-color:#FFD84F;}  #search_content #search #btn_search1{display:none;}  .fixed-nav-search{-ms-box-shadow:0 4px 15px -5px #555;-moz-box-shadow:0 4px 15px -5px #555;-o-box-shadow:0 4px 15px -5px #555;-webkit-box-shadow: 0 4px 15px -5px #555;box-shadow: 0 4px 15px -5px #555;position: fixed !important;z-index: 200;}  .fixed-nav-header{-ms-box-shadow:0 4px 15px -5px #555;-moz-box-shadow:0 4px 15px -5px #555;-o-box-shadow:0 4px 15px -5px #555;-webkit-box-shadow: 0 4px 15px -5px #555;box-shadow: 0 4px 15px -5px #555;position: fixed !important;z-index: 200;}  .empresa{color:#333;background:#F2F2F2;margin:10px auto;width:98%;max-width:1100px;border:#f2f2f2 solid 1px;border-bottom:1px solid #b7b7b7;border-radius: 5px;}  .cita{border-bottom:none}  .empresa #cont div.datos, .empresa #cont div.logo{float:left}  .empresa #cont div.logo{text-align:center;width:290px;padding:10px 0} .logo img {max-width: 209px !important;} .empresa #cont div.logo img{width:250px;height:100px} .empresa #cont div.datos{width:660px;padding:10px}  .empresa #cont div.datos h1{font:normal 30px 'Roboto', Arial, sans-serif;font-weight:bold;padding:0 0;}  .empresa #cont div.datos h1 .fa-star{color:#fbb700;font-size: 15px;}  .empresa #cont div.datos a{color:#333}  .empresa #cont div.datos p{font-size:15px;padding:5px 0;}  .empresa #cont div.datos .fa-map-marker-alt{border-radius: 50%;color:#4285f4;font-size:20px;float:left;margin-right:10px;padding: 5px 0px;height: 30px}  .empresa #cont div.datos p.phone{display:inline-block;font:bold 20px Tahoma, Geneva, sans-serif; margin-left:0;margin-right:20px;float:left;}  .empresa #cont div.datos p.phone .fa-phone{border-radius: 50%;color:#4285f4;padding: 5px 0px;}  .empresa #cont div.datos p.cell{font:bold 20px Tahoma, Geneva, sans-serif;margin-left:0;float:left;}  .empresa #cont div.datos p.what1{font:bold 20px Tahoma, Geneva, sans-serif;margin-left:0;float:left;}  .empresa #cont div.datos p.what2{font:bold 20px Tahoma, Geneva, sans-serif;margin-left:20px;float:left;} .empresa #cont div.datos p.cell1{font:bold 20px Tahoma, Geneva, sans-serif;margin-left:20px;float:left;}  .empresa #cont div.datos p.cell .fa-mobile-alt{margin-right:5px;border-radius: 50%;color:#4285f4;padding:5px 0px;}  .empresa #cont div.datos p.what1 .fa-whatsapp{border-radius: 50%;color:#4285f4;padding:5px 0px;} .empresa #cont div.datos p.what2 .fa-whatsapp{border-radius: 50%;color:#4285f4;padding:5px 0px;}  .empresa #cont div.datos p.cell1 .fa-mobile-alt{margin-right:5px;border-radius: 50%;color:#4285f4;padding:5px 0px;} .empresa #cont div.datos div.dl{display:table;width:100%;}  .empresa #cont div.datos div.dl div{display:table-cell;}  .empresa #cont div.dir{width:400px;}  .empresa #cont div a{display:block}  .empresa #cont div ul{display:table}  .empresa #cont div ul li{display:table-cell;padding:5px 5px 0 0}  .empresa #cont div ul li img{opacity: 0.1;filter: Alpha(opacity=10);}  .lista-rubros h2{font-size: 15px!important;padding: 0!important;margin: 0!important;}   .lista-rubros h5{font-size: 15px!important;padding: 0!important;margin: 0!important;} .empresa #cont div.opciones{width:135px;vertical-align:bottom;}  .empresa #cont div.opciones .sitio{color:#818181;text-align:center;}  .empresa #cont div.opciones .sitio a{color:#818181;display:inline-block;font-size:10px;font-weight:bold;text-decoration: none;}  .empresa #cont div.opciones .sitio .img_sitio{padding:12px 10px 10px 10px;background:url("../imagenes/hotel-icon-plomo.png") no-repeat center;background-size: 100%;margin-right: 5px;}  .empresa #cont div.opciones{width:135px;vertical-align:bottom;}  .empresa #cont div.opciones .sitio{color:#818181;text-align:center;}  .empresa #cont div.opciones .sitio a{color:#818181;display:inline-block;font-size:10px;font-weight:bold;text-decoration: none;}  .empresa #cont div.opciones .sitio .img_sitio{padding: 12px 10px 10px 10px;background: url("../imagenes/hotel-icon-plomo.png") no-repeat center;background-size: 100%;margin-right: 5px;}  .empresa #cont div.opciones a.reservar2{margin-top:5px;background:#0fa100;border:#41b933 solid 1px;color:#FFF;padding:8px 10px; font:bold 14px Arial; display:block;text-align: center;-ms-box-shadow: 0 4px 15px -5px #555;-moz-box-shadow:0 4px 15px -5px #555;-o-box-shadow: 0 4px 15px -5px #555;-webkit-box-shadow:0 4px 15px -5px #555;box-shadow:0 4px 15px -5px #555;}  .empresa #cont div.opciones a.reservar2:hover{background:#41b933;text-decoration:none;color:#FFF;border:#0fa100 solid 1px;}  .empresa #cont div.opciones a.reservar_cita{margin-top:-45px;background:#009587;border:#009587 solid 1px;color:#FFF;padding:8px 10px; font:bold 14px Arial; display:block;text-align: center;-ms-box-shadow: 0 4px 15px -5px #555;-moz-box-shadow:0 4px 15px -5px #555;-o-box-shadow: 0 4px 15px -5px #555;-webkit-box-shadow:0 4px 15px -5px #555;box-shadow:0 4px 15px -5px #555;}  .empresa #cont div.opciones a.reservar_cita:hover{background: #1fa195;text-decoration:none;color:#FFF;border:#009587 solid 1px;}  .empresa #cont div.opciones a.rdelivery{margin-top:5px;background:#FF8F00;border:#FF6F00 solid 1px;color:#FFF;padding:8px 10px; font:bold 14px Arial; display:block;text-align: center;-ms-box-shadow: 0 4px 15px -5px #555;-moz-box-shadow:0 4px 15px -5px #555;-o-box-shadow: 0 4px 15px -5px #555;-webkit-box-shadow:0 4px 15px -5px #555;box-shadow:0 4px 15px -5px #555;}  .empresa #cont div.opciones a.rdelivery:hover{text-decoration:none;color:#FFF;border:#FF8F00 solid 1px;}  .empresa .banner-cita img{width:100%}  #container{background:#FFF;font-family:Arial, Helvetica, sans-serif;}  #page{float:left;width:680px;}  @media screen and (max-width: 650px){#page .reservar_cita { display: block; width: 85%;}} #page .pics{margin:0 0 10px 0;border:#E2E2E2 solid 1px;padding:10px;-ms-box-shadow: 0 4px 5px -5px #555;-moz-box-shadow:0 4px 5px -5px #555;-o-box-shadow: 0 4px 5px -5px #555;-webkit-box-shadow:0 4px 5px -5px #555;box-shadow:0 4px 5px -5px #555;}  #page .pics a:hover img{border:#900 solid 1px}  #fotos {border:#E2E2E2 solid 0;padding:0; overflow:hidden; margin-bottom:15px;}  #containingDiv{width:580px;margin:0 auto;padding:0;height:424px;}   #page .opc2{margin:0px 5px 10px 0;font-size:11px; clear:both;overflow:hidden}    #page .adds{margin:0 0 10px 0;border:#E2E2E2 solid 1px;padding:10px;background:#EAEAEA;}  #page .mmas .imgvideo{float:right; width:230px;height:140px}  #page .mmas .imgvideo img{width:230px;height:140px}  #page .mmas .imgcatalogo{float:left; width:300px;height:140px}  #page .mmas .imgcatalogo img{width:300px;height:140px}  .promo_descripcion{font:normal 14px Helvetica, Arial, sans-serif; color:#473F3D} #page .dat{margin:0 0 0 0;border:#E2E2E2 solid 0;padding:10px 10px 10px 0;font:normal 15px Helvetica, Arial, sans-serif; color:#473F3D;/*-ms-box-shadow: 0 4px 5px -5px #555;-moz-box-shadow:0 4px 5px -5px #555;-o-box-shadow: 0 4px 5px -5px #555;-webkit-box-shadow:0 4px 5px -5px #555;box-shadow:0 4px 5px -5px #555;*/}  #page .dat h2{font:normal 24px "Roboto", sans-serif;padding:5px 0 5px 0;color:#473F3D}  #page .dat h3{font:normal 24px "Roboto", sans-serif;padding:5px 0 5px 0;color:#473F3D} #page .dat h6{font:normal 24px "Roboto", sans-serif;padding:5px 0 5px 0;color:#473F3D}  #page .dat b, #page .dat strong{font:bold 15px Helvetica, Arial, sans-serif; /*text-decoration:underline*/}  #page .dat .strubro{color:#5e5e5e;font-weight: bold;text-decoration: none;}  #page .dat .sub{text-decoration:underline}  #page .dat .red{color:#C00}  #page .dat .blue{color:#00F}  #page .dat .tab{padding-left:0; line-height: 1.375;}  #page .dat .ita{font-style:italic}  #page .dat ul{padding-left:10px}  #page .dat .tabla{padding:10px}  #page .dat table{border:#CACACA solid 1px;border-collapse:collapse; background:#EDEDED}  #page .dat td{border-top:#CACACA solid 1px;padding:2px 3px;}  #page .dat td.ga{width:60%}  #page .dat td.gb{width:40%}  #page .dat .tabla1{padding:10px;font-size:10px}  #page .dat .rubros-rel{border-top:solid 1px #E4E3E3;margin-top:15px;overflow:hidden}  #page .dat .rubros-rel h2{margin: 10px 0}  #page .dat .rubros-rel .lista-rubros {margin-bottom:15px;width: 100%;}  #page .dat .rubros-rel .lista-rubros li{display:inline-block;width: 48%;}  #page .dat .rubros-rel a{display:block; float:left; padding:0 5px; color:#8A8A8A;font-size: 12px;}  #page .dat .rubros-rel a strong{font-weight:bold;text-decoration: none;}  #page .dat .pdf{background:#AB281E url(../imagenes/bpdf.png) no-repeat left center;display:block;padding:15px 5px 15px 55px;font-size:15px;width:200px;color:#FFF;font-weight:bold}  #page .dat .radio{background:url(../imagenes/radio.png) no-repeat 5px 5px;display:block;padding:15px 5px 15px 55px;font-size:15px;width:200px;color:#FFF;font-weight:bold}  #page .dat .galeria{padding: 0; width: 100%;}  #page .dat .galeria li{float:left; margin-bottom: 10px; width: 33.33%;}  #page .dat .galeria li img{width: 100%;}  #page .dat .galeria li .der{margin-left:10px;}  #page .dat .galeria li .med{margin-left:5px; margin-right:5px;}  #page .dat .galeria li .izq{margin-right: 10px;}  #page .dat .catalogo{display: flex; flex-wrap: wrap;padding: 0;width: 100%;}  #page .dat .catalogo li{float:left;margin-bottom:10px;width: 33.33%;}  #page .dat .catalogo li a{display:block;border:1px solid #d7d7d7;background:#FFF;height:200px;position: relative;overflow: hidden;}  #page .dat .catalogo li a img{margin:auto; height:auto; display: block;position: absolute;bottom: 0;top: 0;right: 0;left: 0;width: 90%;-webkit-transition: all 0.5s ease; -moz-transition: all 0.5 ease; -o-transition: all 0.5 ease; }    #page .dat .catalogo li a.der{margin-left:10px;}  #page .dat .catalogo li a.med{margin-left:5px; margin-right: 5px;}  #page .dat .catalogo li a.izq{margin-right:10px;}  #page .dat #content_galeria .catalogo li a{height:150px;}  #page .dat .promoss{margin-bottom:15px;padding:0;width:100%;}  ul.promoss{display: grid;grid-template-columns: 1fr 1fr 1fr; grid-gap: 20px;}@media screen and (max-width: 500px){ul.promoss{display: block;}} .promo_content{padding: 7px 7px 10px;} #page .dat .promoss li img{border-radius:15px 15px 0 0;width: 100%;}  #page .dat .promoss li .promo_cont{border:1px solid #bdbdbd;border-radius:15px;margin-bottom:15px;} #page .dat .promoss li .promo_cont {text-align:center;}  #page .dat .promoss li .promo_cont .promo_imagen{display:grid;overflow:hidden;height: 100% !important;}  #page .dat .promoss li .promo_cont .promo_costo{background:#fbc300;color:#090c00;font-weight:bold}  #page .dat .promoss li .promo_cont .promo_titulo{line-height:1.2;text-align:center;}  #page .dat .promoss li .promo_cont .promo_titulo a{color:#c32728;font-weight:bold} .promoss .promo_descripcion, .promoss .promo_fecha {padding-top: 5px;} #page .dat .promoss li .promo_cont .promo_fecha{color: #090c00;font-size:12px;font-weight:bold}  #page .dat .promoss li .promo_cont .promo_fecha .promo_date{font-weight:normal;}  #page .dat .promoss li .promo_cont .promo_btn{background:#e231ad;border:0;border-radius:5px;color:#FFF;display:inline-block;font-weight: bold;padding: 5px 10px;margin-bottom: 10px;}  #page .dat .promoss li .promo_cont .promo_btn:hover{background: #cc3197;text-decoration:none;}  #page .dat .promoss li .promo_cont .promo_empresa{font-weight:bold;line-height:1.2;height:40px;overflow:hidden;}  #page .dat .promoss li .promo_cont .promo_tipo{margin-bottom:10px;}  #page .dat .content_room{float:left;margin-bottom:10px;width:33.33%;}  #page .dat .content_room .i{margin-right:10px;}  #page .dat .content_room .d{margin-left:10px;}  #page .dat .content_room .m{margin-left:5px; margin-right:5px;}  #page .dat .content_room .content_row{background:#f6f6f6;border:1px solid #e3e3e3;height:200px;margin-top:15px;overflow: hidden;text-align: center;}  #page .dat .content_room .content_row p{padding:10px 5px;font-size:15px;}  #page .dat .content_room .content_row img{width:100%;}  #page .dat .content_room .content_row .btn_book {background:#10a002;border:0;border-radius:3px;color:#FFF;cursor:pointer;display:inline-block;margin-bottom: 10px;padding: 7px 5px;text-decoration: none;font-size: 14px;width: 80%;}  #page .dat .content_room .content_row .btn_book:hover{box-shadow: 1px 1px 3px 0 rgba(0, 0, 0, .27);}  #page .table_vacio{background:#FFFFFF;border-collapse:collapse;width: 100%;}  #page .table_vacio td{background: #FFFFFF;border: solid 1px #FFFFFF;}  #page .tab_act{background: #EEE;border:#f2f2f2 solid 1px;border-bottom: 1px solid #b7b7b7;border-radius: 5px; padding: 10px!important;margin: 10px 5px 10px 0;}  #page .table_info{border-collapse:collapse;width:100%;}  #page .table_info th{background:#b5b5b5;border:#CACACA solid 1px;padding:2px 3px;text-align: center;}  #page .table_info td{background:#dee1e2;border:#CACACA solid 1px;padding:2px 3px;}  ul.ico1 li{background:url(../imagenes/ico1.jpg) no-repeat;padding:2px 13px;background-position:left center}  ul.ico2 li{background:url(../imagenes/ico2.jpg) no-repeat;padding:2px 13px;background-position:left center}  ul.ico3 li{background:url(../imagenes/ico3.jpg) no-repeat;padding:2px 13px;background-position:left center}  ul.ico4 li{background:url(../imagenes/ico4.jpg) no-repeat;padding:2px 13px;background-position:left center}  ul.ico5 li{background:url(../imagenes/ico5.jpg) no-repeat;padding:2px 13px;background-position:left center}  ul.ico6 li{background:url(../imagenes/ico6.png) no-repeat;padding:2px 13px;background-position:left 6px;display: inline-block;float:left;width: 45%;}  .serv{color:#0FA100;margin-top:10px;}  .serv ul li{float: left;margin-right:15px;margin-bottom:15px;width:auto;vertical-align:top;}  .serv ul li .ic_parqueo{background: transparent url("/amarillas/imagenes/ic_parqueo.png") no-repeat center center; background-size: 100%;height:17px;width: 30px;display: inline-block;}  .serv ul li .ic_wifi{background: transparent url("/amarillas/imagenes/ic_wifi.png") no-repeat center center; background-size: 100%;height:17px;width: 30px;display: inline-block;}  .serv ul li .ic_acon{background: transparent url("/amarillas/imagenes/ic_acon.png") no-repeat center center; background-size: 100%;height:17px;width: 30px;display: inline-block;}  .serv ul li .ic_piscina{background: transparent url("/amarillas/imagenes/ic_piscina.png") no-repeat center center; background-size: 100%;height:17px;width: 30px;display: inline-block;}  .serv ul li .ic_lcd{background: transparent url("/amarillas/imagenes/ic_lcd.png") no-repeat center center; background-size: 100%;height:17px;width: 30px;display: inline-block;}  .serv ul li .ic_gym{background: transparent url("/amarillas/imagenes/ic_gym.png") no-repeat center center; background-size: 100%;height:17px;width: 30px;display: inline-block;}  .serv ul li .ic_cafe{background: transparent url("/amarillas/imagenes/ic_cafe.png") no-repeat center center; background-size: 100%;height:17px;width: 30px;display: inline-block;}  .serv ul li .ic_taxi{background: transparent url("/amarillas/imagenes/ic_taxi.png") no-repeat center center; background-size: 100%;height:17px;width: 30px;display: inline-block;}  .btn_promo{background:#53ff2d !important;color:#FFF!important;}  .btn_tienda{background:#008e14 !important;color:#FFF;}  .parpadea {animation-name:parpadeo;animation-duration: 1s;animation-timing-function: linear;animation-iteration-count: infinite;-webkit-animation-name:parpadeo;-webkit-animation-duration: 1s;-webkit-animation-timing-function: linear;-webkit-animation-iteration-count: infinite;}  @-moz-keyframes parpadeo{ 0% { opacity: 1.0; } 50% { opacity: 0.25; } 100% { opacity: 1.0; } }  @-webkit-keyframes parpadeo { 0% { opacity: 1.0; } 50% { opacity: 0.25; } 100% { opacity: 1.0; } }  @keyframes parpadeo{ 0% { opacity: 1.0; } 50% { opacity: 0.25; } 100% { opacity: 1.0; } }  .content_hide{display:none;}  .content_show{display:block;}  .menu_content {text-align:center;font-size:15px;margin-top:30px;}  .menu_content .btn_menu{margin:0 5px 10px 5px;border-radius: 5px;background-color: #eeecec;-webkit-transition: box-shadow 400ms ease;transition: box-shadow 400ms ease;font-family: Poppins, sans-serif;color: #000;font-size: 12px;letter-spacing: 1px;text-transform: uppercase;}  .menu_content .btn_menu2{margin:0 5px 10px 5px;border-radius: 5px;background-color: #eeecec;-webkit-transition: box-shadow 400ms ease;transition: box-shadow 400ms ease;font-family: Poppins, sans-serif;color: #FFF!important;font-size: 12px;letter-spacing: 1px;text-transform: uppercase;}  .menu_content .btn_link{position:relative;display:inline-block;vertical-align: top;text-decoration: none;padding: 9px 30px;text-align: left;cursor: pointer;color: #222222;background-color: #eeecec;}  .menu_content a:hover:not(.active) {box-shadow:1px 1px 3px 0 rgba(0, 0, 0, .27);}  .menu_content .active{margin-right:5px;margin-left: 5px;border-radius:5px;background-color:#4285f4;box-shadow:0 7px 10px 0 rgba(0, 0, 0, .13);color: #fff;}  .menu_content .cat .active {background-color: pink}  .publi{margin:15px 5px 0 0;}  .hide{display:none;}
        .contenedor_logos{width:100%;display: grid;grid-template-columns:1fr 1fr 1fr;grid-gap:20px;margin-top: 20px;}
        .contenedor_logos img{width:100%;height: 185px}
        .contenedor_logos .cont_img{border:2px solid #f2f2f2}
        .contenedor_logos .box{color:black;text-align:center;}
        .contenedor_logos .cont_text{background:#f7f7f7}
        .contenedor_logos .text1{font-size:11px;margin:0;padding:10px;text-transform:uppercase;color:#b7b7b7;height:60px}

        .contenedor_logoscat{width:100%;display: grid;grid-template-columns:1fr 1fr 1fr;grid-gap:20px;margin-top: 20px;}
        .contenedor_logoscat img{width:100%;}
        .contenedor_logoscat .box{color:black;text-align:center;}
        .contenedor_logoscat .text1{    font-size: 15px;margin: 0;padding: 10px;color: #7c7c7c;height: 60px;}
        #right_sidebar{float:right;width:360px}  #right_sidebar .box-cont{border:#E2E2E2 solid 1px;margin-bottom:10px;-ms-box-shadow: 0 4px 5px -5px #555;-moz-box-shadow:0 4px 5px -5px #555;-o-box-shadow: 0 4px 5px -5px #555;-webkit-box-shadow:0 4px 5px -5px #555;box-shadow:0 4px 5px -5px #555;}  .box-cont h1, .box-cont h2, .box-cont h3, .box-cont h4, .box-cont h6{font-family:"Roboto", sans-serif; font-weight:normal}  #right_sidebar .box-cont h1{background:#F2F2F2;border-bottom:#DADADA solid 1px;padding:8px 10px;color:#453F3F;font-size:22px;}  #right_sidebar .box-cont h3{border-bottom:#DADADA solid 1px;padding:8px 10px;color:#453F3F;font-size:20px} #right_sidebar .box-cont h4{border-bottom:#DADADA solid 1px;padding:8px 10px;color:#453F3F;font-size:20px} #right_sidebar .box-cont h6{border-bottom:#DADADA solid 1px;padding:8px 10px;color:#453F3F;font-size:20px} #right_sidebar .box-cont h3{border-bottom:#DADADA solid 1px;padding:8px 10px;color:#453F3F;font-size:20px}  #right_sidebar .box-cont h4{border-bottom:#DADADA solid 1px;padding:8px 10px;color:#453F3F;font-size:20px;font-weight: normal}   #right_sidebar .box-cont td{padding:3px}  #right_sidebar .box-cont td.ti{font:bold 11px Verdana}  #right_sidebar .box-cont td.ti .ienlace{text-decoration:underline}  #right_sidebar .box-cont .gi{padding:10px 10px;font:normal 15px Helvetica, Arial; position:relative;color:#473F3D}  #right_sidebar .box-cont .gi span{ font-size:15px}  #right_sidebar .box-cont .gi .icon{font-size:20px;margin-bottom:10px;}  #right_sidebar .box-cont .gi .icon1{font-size:20px;margin-bottom:10px;}  #right_sidebar .box-cont .gi .icon2{font-size:15px;margin-bottom:10px;line-height: 1.375;}  #right_sidebar .box-cont .gi .address{background: url("../imagenes/icon_maps.png") no-repeat left top;background-size:25px 25px;font-size:15px;}  #right_sidebar .box-cont .gi .phone{background: url("../imagenes/icon_phone.png") no-repeat left top;background-size:25px 25px;}  #right_sidebar .box-cont .gi .cell{background: url("../imagenes/icon_cell.png") no-repeat left top;background-size:25px 25px;}  #right_sidebar .box-cont  .fa-whatsapp{color: #4285f4;margin-right: 10px;}  #right_sidebar .box-cont .gi .skype{background-size:25px 25px;}  #right_sidebar .box-cont .gi .fax{background-size:25px 25px;} .gi .fa-fax{font-size: 20px!important;margin-right: 10px;color:#4285f4}    #right_sidebar .box-cont .dats{padding:10px;font-size:15px}  #right_sidebar .box-cont .dats .tar{padding-top:10px}  #right_sidebar .box-cont .face{padding:10px 0}  #right_sidebar .box-cont a{color:#4285f4;}  #right_sidebar .box-cont a:hover{color:#990;text-decoration:underline}  #right_sidebar .box-cont .suc{margin-bottom:10px;padding:10px 10px 0 10px}  #right_sidebar .box-cont .suc li{border-bottom:#CCC solid 1px; padding:10px 0;clear:both}  #right_sidebar .box-cont .suc li p{padding:3px}  #right_sidebar .box-cont .suc li p span{color:#F00;display:block;padding:2px}  #right_sidebar .box-cont .suc li p.LlameAusp{float:right}  #right_sidebar .box-cont .suc li .btn_masd{background:#2ecc71;border:1px solid #2ecc71; border-radius: 3px;color: #FFF;display: block; margin: 9px 0 0 10px;padding: 5px; width: 85px;}  #right_sidebar .box-cont .Impmap{padding:8px 3px 0 3px;text-align:right;margin-top:1px}  #right_sidebar .box-cont .Impmap a{background:#FFF;border:#000 solid 1px;padding:2px 3px;color:#000;font:bold 11px Arial}  #right_sidebar .box-cont #map_canvas{width:338px;height:240px; border:#DADADA solid 1px}  #map{margin:10px;height: 250px;z-index:0}  #right_sidebar #inf_contacto .fa{float:left;font-size: 25px;text-align:center;width: 30px;}  #right_sidebar #inf_contacto .fas{float:left;font-size: 25px;text-align:center;width: 30px;} #right_sidebar #inf_contacto .fa-envelope-open{font-size: 20px;color:#4285f4}  #right_sidebar #inf_contacto .fab{float:left;font-size: 25px;text-align:center;width: 30px;} #right_sidebar #inf_contacto .fa-phone {margin-top:3px;margin-right: 10px}  <?php if(isset($dias_horario)){ ?>  #right_sidebar #inf_contacto .hoy_horario{background:#EEEEEE;border: solid 1px #CACACA; border-radius: 5px;margin: 15px 0; padding: 3px;}  #right_sidebar #inf_contacto #horario_reloj{background-size:30px;height:40px;float: left;width: 40px;}  <?php }?>  #right_sidebar #inf_contacto .div_table{border-collapse:separate;border-spacing: 0 0;display: table;float:left;margin-top:5px;width: 80%;}  #right_sidebar #inf_contacto .div_table .div_row {display:table-row;width: 100%;}  #right_sidebar #inf_contacto .div_table .div_row .div_cell{display:table-cell;}  #right_sidebar #inf_contacto .div_table .div_row .div_cell .dia_cell{font-weight:bold;}  #right_sidebar #inf_contacto .div_table .div_row .div_cell .hora_cell{color:#4F4F4F;font-weight: bold;font-size:16px ;}  #right_sidebar #inf_contacto ul.otros_servicios{margin:0 10px;width: 95%;}  #right_sidebar #inf_contacto ul.otros_servicios .swifi{background: transparent url(/amarillas/imagenes/ic_wifi-on.png) no-repeat center;background-size:40px;float:left;height:50px;width: 50px}  #right_sidebar #inf_contacto ul.otros_servicios .sdelivery{background: transparent url(/amarillas/imagenes/ic_delivery_on.png) no-repeat center;background-size:40px;float:left;height:50px;width: 50px}  #right_sidebar #ver_horario .div_table{border-collapse:separate;border-spacing: 0 2px;display: table;width: 100%;}  #right_sidebar #ver_horario .div_table .div_row{display:table-row;width:100%;}  #right_sidebar #ver_horario .div_table .div_row .div_cell{display:table-cell;}  #right_sidebar #ver_horario .div_table .div_row .div_cell .dia_cell{color:#5e5e5e;font-weight:bold;}  #right_sidebar #ver_horario .div_table .div_row .div_cell .hora_cell{color:#473f3d;display: inline-block;width: 45%;}  #right_sidebar .estado_abierto{color:#2ecc71;font-weight:bold;}  #right_sidebar .estado_cerrado{color:#e74c3c;font-weight:bold;}  #right_sidebar .padestado{padding-left:15px;}  #right_sidebar #ver_horario .mas_horario{margin-top:10px;}  #right_sidebar .box-cont .btn-mail{background:#FFCB05;border:solid 1px #CACACA;border-radius:5px;color:#000;cursor:pointer;display:table;font-size:18px;margin-bottom:5px;padding:10px 15px;text-decoration: none;width: 90%;}  #right_sidebar .box-cont .btn-mail:hover{color:#000;text-decoration:none;}  #content_qr{text-align:center;}  #content_qr .img_qr{max-width:180px;width:100%;} .qr-pago{width:180px;margin-left:20%}@media screen and (max-width: 500px){.qr-pago {display: flex; width: 100%; margin: auto;}}  #nota{padding:0 10px 10px 10px;background:#FFF}  #nota .text{text-align:center;padding:20px 0 0 0;font-size:10px}  #nota .text1{font-size:11px;text-align:center;line-height:14px;padding-top:10px;color:#5a5d5a} #nota .text1 a{color:#5a5d5a!important;} div#tilc{margin:0 auto;width:1100px; font-size:11px}  div#tilc #col{padding:10px 10px 10px 10px; background:#0969B3 url(../imagenes/bc3.jpg) repeat-x;border-bottom-right-radius:5px;border-bottom-left-radius:5px; margin-bottom:10px}  div#tilc #col #contacts{background:#FFF;text-align:right;padding:0 10px 10px 0;font-size:10px}  div#tilc #col #contacts a{padding:3px;}  div#tilc #col #contacts a:hover{background:#1488C3;color:#FFF}  div#tilc #col #logn{background:#FFF;padding:10px;border-radius:5px}  div#tilc #col #logn h1{font:bold 24px Arial;padding:0 20px;color:#5A5A5A}  div#tilc #col #logn p{font:normal 15px Arial;padding:5px 20px;}  div#tilc #col #logn p.phone{font:bold 20px Tahoma, Geneva, sans-serif; background: url(../imagenes/phone.png) no-repeat left center; margin-left:20px; padding-left:25px}  div#tilc #imf{margin-bottom:10px}  div#main{background:#FFF; margin:0 auto;width:1100px; font-size:11px}  div#main #cont{padding:10px 10px 0 10px}  div#main #cont #datos td{vertical-align:top}  div#main #cont #datos td.iz{width:598px}  div#main #cont #datos td.de{width:362px}  div#main #cont #nota .dat2{font-size:9px;text-align:center;line-height:14px;padding-top:10px}  #banner img{width:100%}  img #banner {width:100%}    #IniFin{background-color:#C8C8C8; margin-top:10px}  .go-top{width:40px;height:40px;text-indent:-9999px;opacity:0.3;position:fixed;bottom:50px;right:100px;display:none;background: url(/noticias/imagenes/gotop.png) no-repeat;}  .scrollup{width:40px;height:40px;opacity:0.3;position:fixed;bottom:50px;right:100px;display:none;text-indent:-9999px;background: url('/turismo/img/icon_top.png') no-repeat;}  #form_mail{text-align:center;}  #form_mail #form_msg{display:none;}  #form_mail input,#form_mail select{font-size: 15px;margin-top:5px;padding: 5px; width: 96%;}  #form_mail input:focus,#form_mail select:focus,#form_mail textarea:focus{outline: none;}  #form_mail select{width:100%;}  #form_mail textarea{font-size:15px;font-family: "Arial", Arial, Helvetica, sans-serif;margin:5px 0;padding: 5px;resize: vertical;width: 97%;}  #form_mail .form-control.error{border:1px solid #e74c3c;}  #form_mail .error{color:#E45635;text-align:left;}  #form_mail #btn_sucess{font-family:Poppins, sans-serif;font-size: 12px;letter-spacing: 1px;text-transform: uppercase;background:#ffcb05;border:0;border-radius:5px;color:#000;cursor:pointer;padding: 10px 0;margin-bottom: 15px;width: 100%;}  #form_mail #btn_sucess:hover{box-shadow:1px 1px 3px 0 rgba(0, 0, 0, .27);}  .fcontent{background:#EEE;margin: 15px auto;width:1100px;border:#f2f2f2 solid 1px;border-bottom: 1px solid #b7b7b7;border-radius: 5px;}  .hrooms{background:#f7f7f7;}  .mhotel{background:#FFF;}  .rooms{padding:20px 30px 35px 30px;}  .rooms .list-hab{position:relative;float:left;width:33.33333%;}  .rooms .list-hab .i{margin-right:10px}  .rooms .list-hab .d{margin-left:10px}  .rooms .list-hab .m{margin:0 5px}  .rooms .list-hab h2{color: #FFFFFF;font-size:14px;font-weight: normal;padding:10px 10px 5px 10px;}  .rooms .list-hab .img-des{overflow:hidden;height:180px}  .rooms .list-hab img{width:100%}  .rooms .list-hab .borde{background:#FFF;border: 1px solid #e3e3e3;height:260px;margin-top:15px;overflow: hidden;text-align: center;}  .rooms .list-hab p{height:auto;}  .rooms .list-hab p a{display:block; padding:10px}  .rooms .list-hab .sinimg{font-size:15px;padding-top:10px; }  .rooms .list-hab .btn_book {margin-top: 10px;background:#10a002;border:1px solid #156b0c;color:#FFF;cursor:pointer;padding: 7px 30px;width: 100%;text-decoration: none;font-weight: bold;font-size: 14px;}  .rooms .list-hab .btn_book:hover{background:#156b0c;}  .rooms h2{font:normal 24px "Roboto", sans-serif;padding:0;color:#473F3D;margin:10px 0 0 0;clear:both;}  .rooms h4{font:normal 24px "Roboto", sans-serif;padding:0;color:#473F3D;margin:10px 0 0 0;clear:both;} .rooms-2 h4{font:normal 24px "Roboto", sans-serif;padding:0;color:#473F3D;margin:10px 0 0 0;clear:both;}   .rooms h5{font:normal 24px "Roboto", sans-serif;padding:0;color:#473F3D;margin:10px 0 0 0;clear:both;} .rooms a{color:#473F3D;text-decoration: none;}  .rooms .list-hab .borde h3 {color:#555555;font-size: 16px;font-weight: bolder;margin-bottom: 10px;margin-top:5px;}  .rooms .list-hab .borde h3 a .fa-star{color:#fbb700;}  .comp{font: normal 24px "Roboto", sans-serif;padding: 0;color: #473F3D;margin: 10px 0 0 0;clear: both}  .rooms .list-news{position:relative;float:left;width:25%;}  .rooms .list-news h2{color: #FFFFFF;font-size:14px;font-weight: normal;padding:10px 10px 5px 10px;}  .rooms .list-news .img-des{overflow:hidden;height:140px}  .rooms .list-news img{width:100%}  .rooms .list-news .i{margin-right:8px}  .rooms .list-news .d{margin-left:8px}  .rooms .list-news .m{margin:0 7px}  .rooms .list-news .borde{background:#f6f6f6;border:1px solid #e3e3e3;height:210px;margin-top:15px;overflow: hidden;text-align: center;}  .rooms .list-news p{height:auto;}  .rooms .list-news p a{display:block;padding:10px}  .rooms .list-news .borde h3 {color:#473F3D;font-size: 16px;font-weight:bolder;margin:10px 0;padding: 0 5px}  .rooms .list-news .borde h3 a .fa-star{color:#fbb700;}  .servicio{margin-top: 10px;}  .servicio .servicio_img{display:inline-block;float:left;margin-right: 10px;width: 33%;}  .servicio .servicio_img img {width:100%;}  .servicio .servicio_table{background:#FFF;border:1px solid #e3e3e3;display:table;float: left;font-size:15px;padding:15px;width: 59%;}  .servicio .servicio_row{display: table-row}  .servicio .servicio_cell{display: block; padding-bottom: 15px;width: 100%;}  .servicio p{color:#473f3d;font-size: 15px;font-weight:bold;margin-bottom:10px;margin-top:0;text-align: left;text-decoration: none;}  .servicio strong{color:#5e5e5e;}  .servicio .izq{padding-left:0;}  .servicio .der{padding-right:0;}  .servicio .servicio_hora span{float:none;}  .coments {background:#FFFFFF;width:100%;margin-bottom: 5px;}  .coments .coments_content{padding:10px 5px 5px 5px;position:relative;overflow:hidden;}  .coments .coments_content #coments_en{margin-left:27px;}  .coments .coments_content #coments_en .coments_in{padding:0;margin:0 6px;display:block;float:left;height: 150px;width: 280px;}  .coments .coments_content #coments_en .coments_in p {margin-bottom:10px;}  .coments .coments_content #coments_en .coments_in .coments_body{background: #64acc4;color:#FFF;padding: 5px;}  .coments a.prev, .coments a.next {background:transparent url(/imagenes/cursores_icon.png) no-repeat;width:45px;height:45px;display:block;position:absolute;top:55px;}  .coments a.prev {left: -4px;background-position: 0 0;}  .coments a.prev:hover {background-position:0 -50px;}  .coments a.prev:focus{outline:none}  .coments a.next {right:0;background-position: -50px 0;}  .coments a.next:hover{background-position:-50px -50px;}  .coments a.prev span, .coments a.next span{display:none;}  .coments a.next:focus{outline:none}  .megamenu_container {z-index: 8000;}  .star_content{padding: 10px;}  .star { display: inline-block;background: url("/imagenes/star.png") no-repeat; width: 25px; height: 25px }  .star_hover { display: inline-block;background: url("/imagenes/star.png") no-repeat; background-position: 0 -25px; width: 25px; height: 25px }  #modal_rating{background:#f9f9f9;display:none;height:85%;overflow-y:auto;width:85%;max-width: 800px; }  .form_table{display: table;margin: 20px auto;}  .form_table .form_row{display: table-row; width: 100%;}  .form_table .form_row .form_cell{display: table-cell;padding-bottom: 10px;vertical-align: top;}  .form_table .form_row .col1{width:10%;}  .form_table .form_row .col2{width:90%;}  .lista-rubros{text-align:center;}  .lista-rubros li {display:inline-block;list-style:none;margin-right:10px;margin-top:10px;}  .lista-rubros li a{background:#f7f7f7;display:block;border-radius:5px;font-size:15px;padding: 5px;}  .lista-rubros li a:hover{background:#e3e3e3;}  .lista-rubros-2{text-align:center}  .lista-rubros-2 a{text-decoration:none}  .lista-rubros-2 li{float:left;width:25%;}  .lista-rubros-2 img{width: 100%;height:143px}  .lista-rubros-2 .cont_img{padding:5px;margin-top:20px;}  .lista-rubros-2 .cont_img .img{border:1px solid #E5E5E5;background:#FFF}  .lista-rubros-2 .cont_img h4{font-size:15px;text-align:center;font-weight:normal}  .lista-rubros-2 .cont_img .text{height:80px;padding-top:15px}  .lista-rubros-2 .cont_img h4{padding:0 8px}  .rooms-2{padding:10px 30px 40px 30px}  .rooms-2 > ul{display:flex;justify-content:center}  .rooms-2 h2{font:normal 24px "Roboto", sans-serif;padding:0;color:#473F3D;margin:10px 0 0 0;clear:both;}  .banner{margin:15px auto}  .banner{margin-top: 25px}  .banner h2{font:normal 24px "Roboto", sans-serif;color:#473F3D;margin:10px 0 0 0;clear:both;}  .banner img{width:100%}  .banner .ban-2 li{float:left;width:50%}  .banner .ban-2 .cont_img{margin:5px}  .banner h2{padding-left: 5px;padding-bottom:10px}  .banner .ban-1{padding:5px}  .banner .ban-1 img{width:100%}  .banner .ban-3 li{float:left;width:33.333%}  .banner .ban-3 .cont_img{margin:5px}  .banner .ban-4 li{float: left;width: 25%}  .banner .ban-4 .cont_img{margin:5px}  .claves{text-align:center;}  .claves li {background:#FFF;border:1px solid #e3e3e3;border-radius:5px;display:inline-block;list-style: none;padding: 5px;margin-right: 5px;margin-top: 10px;}  .modal_iframe{background:#f9f9f9;display: none;height: 85%; overflow-y:auto ;width:85%;max-width: 800px; }  .modal_iframe form{background:#FFFFFF;border:1px solid #e6e9ed;color:#73879c; padding: 20px 30px;}  .modal_iframe form h2{padding-bottom: 10px; font-weight: normal;}  .modal_iframe form hr{border: 1px solid #eeeeee;margin-bottom: 15px; }  .modal_iframe form .mensaje_enviado{display: none;font-size:20px;text-align: center;margin: auto;}  .modal_iframe .form-control{background-color: #fff;box-shadow: inset 0 1px 1px rgba(0,0,0,.075);border: 1px solid #ccc;color:#555; display:block;font-size: 14px;padding:6px 12px;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;width: 100%!important;white-space: normal;}  .modal_iframe .btn{color:#FFF; background-color:#bdc3c7;border:0;border-radius:3px;cursor:pointer;padding: 10px;}  .modal_iframe .btn-info{background-color: #5bc0de;border: 1px solid #46b8da;}  .modal_iframe .btn-info:hover{background-color:#46b8da;}  .modal_iframe .btn-success {background:#26B99A;border:1px solid #169F85;}  .modal_iframe .btn-success:hover{background:#169F85;}  .modal_iframe textarea{font-family:"Arial", Arial, Helvetica, sans-serif;resize:vertical;}  .modal_iframe .header{color:#36A0FF;font-size:16px;padding: 10px;}  .modal_iframe .bigicon {color:#36A0FF; font-size:16px;text-align:right;}  .modal_iframe label.error{color:#e74c3c;}  .modal_iframe .form-control.error{border:1px solid #e74c3c;}  .modal_iframe .error_form{background-color:#dd4b39;border: 1px solid #d73925;border-radius:5px;color:#FFFFFF;display: none;font-size: 14px;margin-top:10px;padding: 10px;}  a.btn_cat {background:#FF8F00;border:#FF6F00 solid 1px;border-radius:5px;color:#FFF;font-size:14px;padding: 5px 10px;}  a.btn_cat:hover{background:#FF6F00;}  footer{background: #333;color:#747474;}  footer #footer_content{margin:0 auto;width:1100px;font-size:12px;padding: 30px 0 60px 0;}  footer #footer_content a{color:#747474;}  footer #footer_content a:hover{color:#FFF;text-decoration: none;}  .ptable{display: table; width:100%;}  .prow{display: table-row;width:100%;}  .pcell{display: table-cell;padding-bottom:10px;vertical-align:top;}  footer #footer_content .i{width:50%;padding-left:10px;}  footer #footer_content .m{width:30%;}  footer #footer_content .d{width:20%;}  footer #footer_content .flogo{width:80%;margin-bottom:20px;padding-left:10px;}  footer #footer_content .flogo img{max-width:350px;width:100%;}  footer #footer_content .enlaces ul{display:inline-block;float:left;width: 33%;}  footer #footer_content .enlaces li.ptit{font-size:14px;margin-bottom: 10px;}  footer #footer_content .enlaces li.ptit a{color:#FFF;}  footer h3{color:#FFF;margin-bottom:10px;font-size: 15px;font-weight: normal;}  footer .pfollow li{display: inline-block;float: left;margin-right: 10px;text-align: center;}  footer .pfollow li a{background: #FFCB05; border-radius: 3px; color:#000;display: block;font-weight:normal;height:50px;width: 50px;}  footer .pfollow li a i{margin: auto 0;color:#FFF;font-weight: normal;font-size: 18px;padding-top:15px;}  footer .pfollow li a.r_fb{background:#4267b2;}  footer .pfollow li a.r_tw{background:#1da1f2;}.public_mobil {display: none;}  footer .pfollow li a.r_gp{background: rgba(255,146,10,1);background: -moz-linear-gradient(45deg, rgba(255,146,10,1) 0%, rgba(255,146,10,1) 22%, rgba(255,10,100,1) 49%, rgba(76,91,255,1) 100%);background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(255,146,10,1)), color-stop(22%, rgba(255,146,10,1)), color-stop(49%, rgba(255,10,100,1)), color-stop(100%, rgba(76,91,255,1)));background: -webkit-linear-gradient(45deg, rgba(255,146,10,1) 0%, rgba(255,146,10,1) 22%, rgba(255,10,100,1) 49%, rgba(76,91,255,1) 100%);background: -o-linear-gradient(45deg, rgba(255,146,10,1) 0%, rgba(255,146,10,1) 22%, rgba(255,10,100,1) 49%, rgba(76,91,255,1) 100%);background: -ms-linear-gradient(45deg, rgba(255,146,10,1) 0%, rgba(255,146,10,1) 22%, rgba(255,10,100,1) 49%, rgba(76,91,255,1) 100%);background: linear-gradient(45deg, rgba(255,146,10,1) 0%, rgba(255,146,10,1) 22%, rgba(255,10,100,1) 49%, rgba(76,91,255,1) 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff920a', endColorstr='#4c5bff', GradientType=1 );  }  .tamcita{background:#fff;height:127px}  .tamcita{background:#fff;height:127px}  .tamgal{background:#fff;height:115px}  .tamqr{background:#fff;height:180px}  .tamhab{background:#fff;height:132px}  .tammas{background:#fff;height:140px}  .tamama{background:#fff;} .tamama img{width: 100%;height: 103px} .tamotras{background:#fff;height:143px}  .tamadd{height: 37px}  .blur-up {-webkit-filter: blur(5px);filter: blur(5px);transition: filter 400ms, -webkit-filter 400ms;}  .blur-up.lazyloaded {-webkit-filter: blur(0);filter: blur(0);}  .fade-box .lazyload, .fade-box .lazyloading {opacity: 0;transition: opacity 0s;}  .fade-box img.lazyloaded {opacity: 1;}  #fotos img {border: 0;width: 100%;height: 424px;}  #fotos .tamslider{background:#fff;height: 424px}  .slick_controls {display: inline-block;background: #f0f1f2;border: 1px solid #f0f1f2;border-radius: 50%;cursor: pointer;padding: 10px 15px;}  .slick_controls:hover {border: 1px solid #5a5a5a;color: #5a5a5a;}  .slick_previous{position: absolute;left: 5px; top:200px;z-index: 100;}  .slick_next{position: absolute;right: 5px; top:200px;z-index: 100;}  .slider_fotos{position: relative;margin-right: 5px}  #content_14 p{margin: 12px 0}  .enl{margin: 10px 10px 10px 15px}  .ico_web{font-size:15px;padding:5px 0 10px 0px;} .gi .fa-globe-americas{margin-right: 10px;font-size: 20px!important; color:#4285f4}  .ico_email{font-size:15px;padding:5px 0 5px 5px;} .gi .fa-envelope-open{margin-right: 15px}  .social .social_text{border-bottom: 0!important; padding-left: 0!important; color:#453f3f;margin-top:20px;padding:5px 0;font-size:20px;font-family: 'Roboto', Helvetica, Arial;font-weight: normal}  .color_blanco{background: #FFF;}  .bord_30{width: 30%}  .bord_70{width: 70%}  .bord_100{display:block;width:100%}  .bord_90{width: 90%}  .bord_15{width: 15%}  .font_hoy{font-size: 15px!important;}  .act{font-size: 17px!important;}  .fcontent .ver_mas{font-weight: normal}  #nota .text h2{font-weight: normal;font-size: 25px;}  .gi .fa-phone{font-size: 20px!important;color:#4285f4}  .gi .fa-map-marker-alt{font-size: 20px!important;color:#4285f4;margin-right: 10px;height: 30px}  .gi .fa-mobile-alt{font-size: 20px!important;color:#4285f4;margin-right: 10px}  .gi .fa-skype{color:#00aff0;margin-right: 10px}  .fa{display:inline-block;font:normal normal normal 14px/1 FontAwesome;font-size:inherit;text-rendering:auto;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.fa-search:before{content:"\f002"}.fa-cog:before{content:"\f013"}.fa-map-marker:before{content:"\f041"}.fa-mobile:before{content:"\f10b"}.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0}  .fa-clock {text-align: center;font-size: 25px;margin-top: 8px;float: left;width: 40px;}  #horario_reloj .far{color:#38cf78}  #horario_reloj .fas{color:#e74c3c;width: 0px!important;margin-left: 8px}  .suc strong{font-size: 15px}  .btn_catalogo{background: #FF6F00!important;color:#fff!important;}  .rig_direc{margin-bottom: 5px}  #personal .fondo img{width: 100%;height: 166px}  #personal .contenedor .cont_izq{float: left;width: 25%}  #personal .contenedor .cont_der{float: left;width: 75%}  #personal .empresa{border:0}  #personal .empresa h1{font: normal 30px 'Roboto', Arial, sans-serif;font-weight: bold;padding: 0 0;}  #personal .empresa .cont_izq .logo{margin-top: -100px;margin-bottom: 20px}  #personal .empresa .cont_izq img{position: relative;border-radius: 18px}  #personal .info_profesional{margin-top: 10px}  #personal .info_profesional p{font-size: 17px;margin-bottom: 3px;font-weight: bold}  #personal .info_profesional .institucion{font-size: 19px}  .opc2 p{font-size: 15px;padding: 5px 0;}  .opc2 .fa-map-marker-alt {border-radius: 50%;color: #4285f4;font-size: 20px;float: left;margin-right: 15px;padding: 5px 0px;height: 30px;}  .opc2 div.datos p.phone .fa-phone{ color: #4285f4; padding:5px 0px}  .opc2 div.datos p.what1 .fa-whatsapp{ color: #4285f4; padding:5px 0px}.opc2 div.datos p.what2 .fa-whatsapp{ color: #4285f4; padding:5px 0px} .opc2 div.datos p.what1 {display: inline-block;font: bold 20px Tahoma, Geneva, sans-serif;margin-left: 0;margin-right: 20px;float: left;} .opc2 div.datos p.what2 {display: inline-block;font: bold 20px Tahoma, Geneva, sans-serif;margin-left: 0;margin-right: 20px;float: left;}   .opc2 div.datos p.phone {display: inline-block;font: bold 20px Tahoma, Geneva, sans-serif;margin-left: 0;margin-right: 20px;float: left;} .opc2 div.datos p.cell {font: bold 20px Tahoma, Geneva, sans-serif;margin-left: 0;float: left;margin-right: 20px} .opc2 div.datos p.cell1 {font: bold 20px Tahoma, Geneva, sans-serif;margin-left: 0;float: left;} .opc2 .fa-mobile-alt {border-radius: 50%;color: #4285f4;padding: 5px 0px;font-size: 20px;}  .opc2 .datos .opciones{margin: 20px 0;}  .opc2 .datos .opciones a{text-decoration: none; border: #009587 solid 1px;color: #FFF;padding: 8px 10px;font: bold 14px Arial;background: #009587;text-align: center;-ms-box-shadow: 0 4px 15px -5px #555;-moz-box-shadow: 0 4px 15px -5px #555;-o-box-shadow: 0 4px 15px -5px #555;-webkit-box-shadow: 0 4px 15px -5px #555;box-shadow: 0 4px 15px -5px #555;}  .megamenu_right .fa-angle-right{float: right;margin-top: 5px}  #logo8 .logo_betm8 img{height: 23px;}  #logo8 .logo_betm8{float: left;background-color: #000;display: inline-block;padding: 11px 10px; }  #logo8 .logo_img{float: left;padding: 11px 10px;width: auto;}  #logo8 .logo_img img{width: 210px;height: 23px;}  #logo8 .menu8{float: right;padding: 20px 10px 14px 10px;}
        #right_sidebar .box-cont .social{overflow:hidden;margin-bottom: 5px;}
        #right_sidebar .box-cont .social ul{display:inline;padding: 10px;overflow:hidden;}
        #right_sidebar .box-cont .social li{float:left;margin-left:5px;display: block;}
        #right_sidebar .box-cont .social li a{display: block;width: 36px; height: 36px; text-align: center;}
        #right_sidebar .box-cont .social li a:hover{background: #333; color: #FFF;}
        #right_sidebar .box-cont .social li i{display: block;color: #FFF;padding: 6px 4px;}
        #right_sidebar .box-cont .social .i_0{background: #0866FF;}
        #right_sidebar .box-cont .social .i_1{background: #000;}
        #right_sidebar .box-cont .social .i_2{background: #000;}
        #right_sidebar .box-cont .social .i_3{background: #FE1101;}
        #right_sidebar .box-cont .social .i_4{background: #00A9F0;}
        #right_sidebar .box-cont .social .i_5{background: #589541;}
        #right_sidebar .box-cont .social .i_6{background: #00D33A;}
        #right_sidebar .box-cont .social .i_7{background: #26A5E5;}
        #right_sidebar .box-cont .social .i_8{background: #007EBB;}
        #right_sidebar .box-cont .social .i_9{background: #008e14;}
        #right_sidebar .box-cont .social .i_10{background: #BD3BDB;}
        
        .content_team .cont_izq{width: 20%;float: left}
        .content_team .cont_der{width: 80%;float: left}
        .content_team img{width: 100%;border-radius: 35px}
        .content_team ul li{margin: 20px}
        .content_team .cont_der .der{margin-left: 20px;}
        .content_team .cont_der .der p{padding-top: 10px;margin-bottom: 3px;font-size: 15px;}
        .content_team .cont_der .der span{font-weight: bold;font-size: 15px}
        .content_team a{color:#473F3D!important;}
        .texto_tienda{margin-top: 10px; margin-bottom: 20px;line-height: 1.37}  button{border-width:0px}
        #content_qr img{height: 180px}
        #logo_betm img{width: 78px; height:48px;}
        footer #footer_content .flogo img{height: 40px}
        
        #page .dat .tab .tienda_link{margin-top: 10px}
        #page .dat .tab .tienda_link a{color: #4285f4; font-size: 18px}
        #page .dat .tab .tienda_link a:hover{color:#990}
        <?php if($tipo==8){?>
            body{background: <?=$colores?>}  #header{display: none}
        footer li{margin-bottom: 10px}
        footer .titulo_pie {color: #fff;}
        footer .titulo2 {color: #fff;margin-bottom: 10px;font-size: 15px;font-weight: 400;}
        footer .contac {padding: 10px 0;}
        footer .comen {font: normal 15px Arial;}


       <?php } ?>
        .map_goo{margin: 15px;color:#4285f4}

        @media screen and (max-width:1400px) {header{width:98%;max-width: 980px}#search_content #search{width:98%;max-width: 980px}#wrapper{width:98%;max-width: 980px}.empresa{width:98%;max-width: 980px}.fcontent{width:98%;max-width: 980px}  div#tilc{width:980px;}div#main{width:980px;}  #container{ padding:0}#page{width:610px}#containingDiv{width:100%;}footer #footer_content{width:980px;}.banner{width:990px}

        }
        @media screen and (max-width: 980px) {#search_content #search{width:99%;}#wrapper{width:100%}#containingDiv{height:420px;}#container{padding:0}#page{width:58%;}#right_sidebar{width:40%}#banner img{width:100%;height:auto}.empresa{width:99%;}.fcontent{width:99%;}.empresa #cont{overflow:hidden;}.empresa #cont div.logo{width:30%;}.empresa #cont div.logo img{width:100%;height:auto}header{width:100%}.empresa #cont div.datos{width:65%}.empresa #cont div.datos h1{font:bold 25px Arial;}.empresa #cont div.opciones{width:33%;}.serv ul li{margin-right: 10px;}.menu_content li{width:33%;}.empresa #cont div.dir{width:35%}#banner img{width:100%;height:auto}#right_sidebar .box-cont{margin-top:10px}#right_sidebar .box-cont #map_canvas{width:100%;height:240px;}#page .mmas .imgvideo{float:left;width:49%;height:auto}#page .mmas .imgcatalogo{width:49%;height:auto}#page .mmas .imgvideo img{width:100%;height:auto}#page .mmas .imgcatalogo img{width:100%;height:auto}ul#thumbs ul{height:auto;}ul#thumbs li{float:left;margin:2px;width:19%;height:auto;}ul#thumbs a{display:block;}ul#thumbs a img{width:100%;height:auto}ul#ithumbs{height:auto;}ul#ithumbs li{float:left;margin:2px;width:10%;height:auto;}ul#ithumbs a img{width:100%;height:auto}#page .dat img{width:100%;height:auto}.scrollup{bottom:45px;right:10px;}#empresa #cont div.opciones .sitio .img_sitio{padding: 15px 15px 15px 15px;}footer #footer_content{width:99%;}footer .pfollow li a{height:40px;width: 40px;}footer .pfollow li a i{padding-top:10px;}  }
        @media screen and (max-width: 920px) {  header{margin:0;width:100%;}  header #LogoOptions #logo_betm{padding: 17px 0;width: 17%;height:auto }  header #LogoOptions #logo_betm img{display:block;height:100%;margin: 0 auto;max-height:44px;width:auto;}  header #LogoOptions #logo_img{padding:17px 10px;width:60%;}  header #LogoOptions #logo_img img{display:block;height:100%;max-height:44px;width:auto;}  #nav{display: none;}  header .menu-nav {display:inline-block;position:absolute;right:17px;top:14px;cursor:pointer;line-height:28px;font-size:14px;}  .wraper{max-width:1200px;margin:auto;padding:0 20px;position:relative;z-index: 10;}  .content{ z-index: 10; color: #2a2a2a;}  .content a{color:#2a2a2a;text-decoration: underline;}  .content a:hover { text-decoration: none;}  .menu-column{width:100%;position:relative;display: block;float: left;box-sizing: border-box;padding-bottom: 20px;}  .menu-main {color: #FFF;}  .menu-main a{color:#FFF;font-style: normal !important;text-decoration: none;}  .menu-main a:hover{text-decoration: underline;}  #menu-main-menu{position:relative;width:100%;text-align:left;}  #menu-main-menu li a{display:block;margin:18px 0; }  #menu-main-menu li{position:relative;border-top:dotted 1px #5f5454}  #menu-main-menu .sub-menu{display:none;border-bottom:none;}  #menu-main-menu .sub-menu li{border-top:solid 1px #584E4E;line-height:20px;text-transform: uppercase;background-color:#4E4545;}  #menu-main-menu .sub-menu li a{margin: 10px;display: inline-block}  #menu-main-menu .visible{display:block;}  .open-menu-link{display:none;position:absolute;right:0;top:0;line-height:40px;color:#FFF;font-size:18px;cursor:pointer; background-color:#4E4545;width:40px; text-align:center; margin-top:6px;font-weight:bold}  #form_search{display:inline-block;margin:10px 10px 0 10px;position:relative;width: 90%;}  #form_search input{float:left;padding:5px;width:78%;}  #form_search button{border:0;background:transparent;color:#FFF;float: right;font-size: 20px;margin-top: 2px;}  .btn_menu{border: none;background: transparent;}  .sidebar-offcanvas{display:block;}  .btn_menu2{border:none;background:transparent;}  .sidebar-offcanvas{display: block;}  #fotos img{height: 311px}  #fotos .tamslider{height: 311px}  .slick_previous{top:150px} .slick_next{top:150px}  #page .dat{padding-left: 10px}  .empresa #cont div a {display: block;}  #telefonos .c_llamar {color: #4285f4;float: left;font-size: 10px;padding-top: 4px;}  #telefonos .c_llamar .fa-phone {padding: 5px 0px;}  #telefonos .c_llamar .fa-mobile-alt {padding: 5px 0px;}  #telefonos .c_llamar .fa-phone, #telefonos .c_llamar .fa-mobile-alt {border-radius: 50%;color: #4285f4;font-size: 20px;}  #telefonos .c_llamar .fa-phone{margin-right: 10px}  #telefonos .c_llamar .fa-mobile-alt{margin-right: 10px}  .telf1{float: left}  .cel1{float: left}  .call1{float: left}  .telf2{float: left}  .cel2{float: left}  .call2{float: left}  #page .opc2{padding-left: 10px}  #personal .contenedor .cont_izq{width: 35%}  #personal .contenedor .cont_der{width: 65%} #telefonos .c_llamar .fa-whatsapp{margin-right: 10px} .empresa #cont div.datos p.what2{margin-left: 0px} .empresa #cont div.datos .fa-map-marker-alt{margin-right: 15px} }
        @media screen and (max-width: 768px) {
            .contenedor_logoscat {
                grid-template-columns: 1fr;
            }
            .contenedor_logoscat .box a {
                text-decoration: none;
                color: #3d3b3b;
                display: flex;
            }
            .contenedor_logoscat. cont_img {
                width: 50%;
            }
            .contenedor_logoscat .contenedor_logoscat .text1 {
                font-size: 17px;
                padding: 10px 0px 0px 20px;
            }
            .contenedor_logoscat .cont_text {
                position: relative;
                width: 50%;
            }
            .contenedor_logoscat p.text1 {
                text-align: left;
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
            }
        }
        @media screen and (max-width: 650px) {  header{margin:0;width:100%;}  header #LogoOptions #logo_betm{padding: 17px 0;width: 17%;height:auto }  header #LogoOptions #logo_betm img{display:block;height:100%;margin: 0 auto;max-height:44px;width:auto;}  header #LogoOptions #logo_img{padding:17px 10px;width:60%;}  header #LogoOptions #logo_img img{display:block;height:100%;max-height:44px;width:auto;}   #nav{display: none;}  header .menu-nav {display:inline-block;position:absolute;right:17px;top:14px;cursor:pointer;line-height:28px;font-size:14px;}  .wraper{max-width:1200px;margin:auto;padding:0 20px;position:relative;z-index: 10;}  .content{ z-index: 10; color: #2a2a2a;}  .content a{color:#2a2a2a;text-decoration: underline;}  .content a:hover { text-decoration: none;}  .menu-column{width:100%;position:relative;display: block;float: left;box-sizing: border-box;padding-bottom: 20px;}  .menu-main {color: #FFF;}  .menu-main a{color:#FFF;font-style: normal !important;text-decoration: none;}  .menu-main a:hover{text-decoration: underline;}  #menu-main-menu{position:relative;width:100%;text-align:left;}  #menu-main-menu li a{display:block;margin:18px 0; }  #menu-main-menu li{position:relative;border-top:dotted 1px #5f5454}  #menu-main-menu .sub-menu{display:none;border-bottom:none;}  #menu-main-menu .sub-menu li{border-top:solid 1px #584E4E;line-height:20px;text-transform: uppercase;background-color:#4E4545;}  #menu-main-menu .sub-menu li a{margin: 10px;display: inline-block}  #menu-main-menu .visible{display:block;}  .open-menu-link{display:none;position:absolute;right:0;top:0;line-height:40px;color:#FFF;font-size:18px;cursor:pointer; background-color:#4E4545;width:40px; text-align:center; margin-top:6px;font-weight:bold}  #form_search{display:inline-block;margin:10px 10px 0 10px;position:relative;width: 90%;}  #form_search input{float:left;padding:5px;width:78%;}  #form_search button{border:0;background:transparent;color:#FFF;float: right;font-size: 20px;margin-top: 2px;}  .btn_menu{border: none;background: transparent;}  .sidebar-offcanvas{display:block;}  .btn_menu2{border:none;background:transparent;}  .sidebar-offcanvas{display: block;}  #page .tab_act{background:#FFF;border:0;border-radius:0;padding:5px!important;margin:10px 5px 10px 0;}#search_content #search input,#search_content #search select{width: 43%;}  #container{padding:0}  #page{float:none;width:100%;}  #right_sidebar{float:none;width:100%}  .empresa #cont div.opciones{vertical-align: top;}  #containingDiv{height:250px;}  .menu_content li {width: 49.79%;}  .rooms .list-hab .img-des{height: auto;}  .rooms .list-hab .i{margin-right:6px}  .rooms .list-hab .d{margin-left:6px}  .rooms .list-hab .m{margin:15px 3px 0 3px;}  .rooms .list-hab .borde{height:240px;}  .rooms .list-news .img-des{height: 85px;}  .rooms .list-news .i{margin-right:4px}  .rooms .list-news .d{margin-left:4px}  .rooms .list-news .m{margin:15px 4px 0 4px;}  .rooms .list-news .borde{height:190px;}  .servicio .servicio_img{margin-right: 10px;width: 35%;}  .servicio .servicio_table{float: left;padding:15px;width: 57%;}  .servicio .servicio_hora span{display:block;float: none;}  .ptable{display: table; width:100%;}  .prow{display: block;width:100%;}  .pcell{display: inline-block;padding-bottom:10px;vertical-align:top;}  footer #footer_content .i {width: 95%;padding-left: 10px;}  footer #footer_content .m {width: 50%;padding-left: 10px;}  footer #footer_content .d {width: 40%;}  .lista-rubros-2 li{width: 100%}  .rooms-2 > ul{display: inline-block}  .lista-rubros-2 img{height: auto}  .banner .ban-2 li {width: 100%;}  .banner{width: 100%}  .banner .ban-3 li{width: 50%}  .banner .ban-4 li{width: 100%}  #page .dat h3{font-size: 18px;padding: 18px;margin: 2px 10px;}  #page .dat h6{font-size: 18px;padding: 18px;margin: 2px 10px;}  #page .dat h3:hover{color:#FFF}  #page .dat h6:hover{color:#FFF}  #right_sidebar .box-cont h3{font-size: 18px;padding: 18px;margin: 2px 10px;}  #right_sidebar .box-cont h3:hover{color:#FFF}  #right_sidebar .box-cont h4{font-size: 18px;padding: 18px;margin: 2px 10px;}  #right_sidebar .box-cont h6{font-size: 18px;padding: 18px;margin: 2px 10px;}  #right_sidebar .box-cont h4:hover{color:#FFF}  #right_sidebar .box-cont h6:hover{color:#FFF}  .contenedor_logos{grid-template-columns: 1fr 1fr;}  .contenedor_logos img{height: 147px!important}
            .contenedor_logoscat {grid-template-columns: 1fr; margin-top: 10px;}
            .contenedor_logoscat .box a {text-decoration: none;color: #3d3b3b;display: flex;}
            .contenedor_logoscat .cont_img {width: 50%;}
            .contenedor_logoscat .text1 {font-size: 17px;padding: 10px 0px 0px 20px;}
            .contenedor_logoscat .cont_text {position: relative;width: 50%;}
            .contenedor_logoscat p.text1 {text-align: left;position: absolute;top: 50%;transform: translateY(-50%);}
            .tamcita{height:auto}  .tamgal{height:auto}  .tamqr{height:auto}  .tamhab{height:auto}  .tammas{height:auto}  .tamama{height:auto}  .tamotras{height:auto}  #fotos img{height: 252px}  .slick_previous{top:120px }  .slick_next{top:120px}  #fotos .tamslider{height: 252px}  .slider_fotos{margin-right: 0}  .social_text{padding-bottom: 5px!important;margin-left: 0px!important;}  .mbtn_catalogo{background: #FF6F00!important;color:#fff!important;}  .empresa #cont div.datos .fa-map-marker-alt{height: 30px;margin-right: 15px}  #personal .empresa h1{font-size: 22px;padding: 10px}  #personal .contenedor .cont_izq{width: 100%}  #personal .contenedor .cont_der{width: 100%;}  #personal .empresa h1{text-align: center}  #personal .empresa .cont_izq .logo{text-align: center}  #personal .fondo img{height: 150px}  #personal .info_profesional p{text-align: center}  #personal .info_profesional{margin-top: 0}  #personal .cont_der .datos{margin-bottom: 15px}  .opc2 div.datos p.cell{float: none;}  .opc2 div.datos p.phone{display: block;font: bold 20px Tahoma, Geneva, sans-serif;margin-left: 0;margin-right: 10px;float: none;}  .opc2 .datos .opciones{margin-top: 0;margin-left: 20px}  .opc2 .datos{border-bottom:1px solid #E5E5E5;border-top:1px solid #E5E5E5 }  .opc2 .datos .dl{margin-top: 20px; margin-bottom: 20px;}  .opc2 .dir{margin-left: 16px}  .telf1{float: none}  .cel1{float: none}  .call1{float: none}  .telf2{float: none}  .cel2{float: none}  .call2{float: none}  .telf_2{margin-top: 15px}  .cel_2{margin-top: 15px}
        <?php if($tipo==8){?>#header{display: block}  <?php } ?>  .texto_tienda{ padding: 10px } }
        @media screen and (max-width: 500px) {header #LogoOptions #logo_betm img{max-height:24px;width: 65%;}header #LogoOptions #logo_img img{max-height:24px;width: 100%;}  #nav{display: none;}  #search_content #search input{border-radius:3px;float:left;display:inline-block;margin-bottom:0;width:93%;}#search_content #search select{display:none;float:left;margin-top:10px;width:65%;}#search_content #search button{display:none;float:right;margin-top:10px;width:100px;margin-right:3px;}#search_content #search #btn_search1{cursor:pointer;float:right;display:inline-block;color:#5a656f;font-size:20px;padding:3px 15px;text-align:center;}#bquery{background: #FFF url("/amarillas/imagenes/icon_search.png") no-repeat;background-position:right 10px center;}.serv ul li{width: 45%;}.comp{color:#444;font-size:18px;padding:18px;margin:2px 10px}#containingDiv{height:240px;}.empresa #cont div.datos{padding:0;float:none;}.empresa #cont div.datos h1{font-size:22px;padding:10px;}.empresa #cont div.datos p{margin:0 16px;}.empresa #cont div.datos p.phone{}.empresa #cont div.datos p.cell{margin-left:16px;float:none;} .empresa #cont div.datos p.cell1{margin-left:16px;float:none;}  .empresa #cont div.datos p.what1{float:none;}  .empresa #cont div.datos p.what2{margin-left:0;float:none;}.empresa #cont div.datos div.dl{display:block;width:100%;}.empresa #cont div.datos div.dl div{display:block;}#telefonos{margin-bottom: 25px;margin-left: 16px}#telefonos .c_llamar{color:#6fba1a;float:left;font-size:10px;padding-top:4px;}#telefonos .c_llamar .fa-phone, #telefonos .c_llamar .fa-mobile-alt{border-radius:50%;color:#4285f4;font-size: 20px;}#telefonos .c_llamar .fa-phone{padding:5px 0px;margin-right: 10px }#telefonos .c_llamar .fa-mobile-alt{padding: 5px 0px;margin-right: 15px}.empresa #cont div.datos .fa-map-marker{margin-right:15px;}.empresa #cont div.datos p.phone{display:block;font:bold 20px Tahoma, Geneva, sans-serif;margin-right:10px;float:none;}#page .dat .galeria li{margin-bottom: 5px;width: 50%;}#page .dat .galeria li .der{margin-left:0;}#page .dat .galeria li .med{margin-left:0;margin-right:0;}#page .dat .galeria li .izq{margin-right:0;}#page .dat .galeria li .mder{margin-left:4px;}#page .dat .galeria li .mizq{margin-right:4px;}#page .dat .catalogo li{margin-bottom:5px;width:50%;}#page .dat .catalogo li a.der{margin-left: 0;}#page .dat .catalogo li a.med{margin-left:0;margin-right:0;}#page .dat .catalogo li a.izq{margin-right: 0;}#page .dat .catalogo li a.mder{margin-left: 4px;}#page .dat .catalogo li a.mizq{margin-right: 4px;}#page .dat .promoss li{margin-bottom:15px;width:100%;}#page .dat .promoss li div.der{margin-left:0;}#page .dat .promoss li div.med{margin-left:0;margin-right:0;}#page .dat .promoss li div.izq{margin-right: 0;} #page .dat .promoss li div.mder{margin-left: 0;}  #page .dat .promoss li div.mizq{margin-right: 0;}#page .dat .content_room{float:none; margin-bottom: 10px; width: 100%;}#page .dat .content_room .content_row{height:auto;}#page .dat .content_room .i{margin-right: 0;}#page .dat .content_room .d{margin-left: 0;}#page .dat .content_room .m{margin-left: 0; margin-right: 0;}.rooms{padding: 0;}.rooms1{padding: 10px;}.rooms .borde h2{margin-bottom: 5px;}.rooms .list-hab{position:relative;float:none;display:block;width:100%;}.rooms .list-hab .borde{height:auto;}.rooms .list-hab .btn_book{display:inline-block;margin-top: 0;margin-bottom:10px;width: auto;}  .rooms .list-hab .i{margin-right:0}  .rooms .list-hab .d{margin-left:0}  .rooms .list-hab .m{margin:15px 0 0 0;}  .rooms .list-news {position:relative;display:block;width:50%;}  .rooms .list-news .img-des{height: 110px;}  .rooms .list-news .borde{height: 200px;}  .rooms .list-news .btn_book{display:inline-block;margin-top: 0;margin-bottom:10px;width: auto;}  .rooms .list-news .i{margin-right:0}  .rooms .list-news .d{margin-left:0}  .rooms .list-news .m{margin:15px 0 0 0;}  .rooms .list-news .im{margin-right: 3px;}  .rooms .list-news .dm{margin-left: 3px;}  .empresa #cont div.logo{text-align: center;width:100%;}  .empresa #cont div.logo img{max-width: 250px;}  .empresa #cont div.datos{width:100%}  .empresa #cont div.datos h1{text-align: center;}  .empresa #cont div.dir{width:99%}  .empresa #cont div a{display:block; }  .empresa #cont div ul{display:block}  .empresa #cont div ul li{display:block;}  .empresa #cont div.opciones{width:100%;}  .empresa #cont div.opciones a.reservar2{margin-top:5px;background-image:none;border:none;padding:8px 10px;color:#FFF;text-align:center;font-size:18px} .empresa #cont div.opciones a.reservar_cita{margin-top:5px;background-image:none;border:none;padding:8px 10px;color:#FFF;text-align:center;font-size:18px} .empresa #cont div.opciones a.reservar2:hover{background-image:none;text-decoration:none;border:none}   #page .dat ul.ico6 li{width: 42%;}  .servicio .servicio_img{display:block;float:none;margin-right: 0;width: 100%;}  .servicio .servicio_table{padding:15px;width: 90%;}  .fcontent{margin: 30px auto;-ms-box-shadow: 0 0 0 0;-moz-box-shadow:0 0 0 0;-o-box-shadow: 0 0 0 0;-webkit-box-shadow:0 0 0 0;box-shadow:0 0 0 0;}  #publi_1{display: none;}  #right_sidebar .box-cont .dats{padding: 10px 0;}  #right_sidebar .box-cont, #page .dat{padding: 0;margin: 0;border:0; -ms-box-shadow:0 0 0 0 ;-moz-box-shadow:0 0 0 0;-o-box-shadow: 0 0 0 0;-webkit-box-shadow:0 0 0 0;box-shadow:0 0 0 0;}  #right_sidebar .box-cont h3{border: 0;}#right_sidebar .box-cont h4{border: 0;} #right_sidebar .box-cont h6{border: 0;}  #right_sidebar .box-cont h3, #page .dat h3, #page .dat h6,.rooms h3{color:#444;font-size:18px;padding: 18px; margin: 2px 10px;} #right_sidebar .box-cont h4, #page .dat h4{color:#444;font-size:18px;padding: 18px; margin: 2px 10px;}  #right_sidebar .box-cont h6, #page .dat h6,.rooms h6{color:#444;font-size:18px;padding: 18px; margin: 2px 10px;} #right_sidebar .box-cont h3, #page .dat h3, #page .dat h6, #page .dat h2,.rooms-2 h3{color:#444;font-size:18px;padding: 18px; margin: 2px 10px;} #right_sidebar .box-cont h4, #page .dat h4{color:#444;font-size:18px;padding: 18px; margin: 2px 10px;}  #right_sidebar .box-cont h6, #page .dat h6,.rooms-2 h6{color:#444;font-size:18px;padding: 18px; margin: 2px 10px;}  #content_1, #content_11, #content_12, #content_2, #content_3, #content_4, #content_5, #content_6, #content_7, #content_8, #content_9, #content_10, #content_13, #content_14{display: block;}  .menu_content{display:none;}  .accordion {background: #eee;cursor: pointer;margin: 2px 10px;border: 0;border-radius: 15px;margin-bottom: 8px!important;}  h2.active, .accordion:hover {background-color: #4285f4;color:#FFF!important;}  #right_sidebar .box-cont h3:hover, #page .dat h3:hover,.rooms h3:hover{color:#FFF;} #right_sidebar .box-cont h4:hover, #page .dat h4:hover{color:#FFF;} #right_sidebar .box-cont h6:hover, #page .dat h6:hover,.rooms h6:hover{color:#FFF;}  #right_sidebar .box-cont h3.active, #page .dat h3.active, #page .dat h3.active, .rooms h3.active{color:#FFF;background: #4285f4}  #right_sidebar .box-cont h4.active, #page .dat h4.active,.rooms h4.active, .rooms-2 h4.active{color:#FFF;background: #4285f4}  #right_sidebar .box-cont h6.active, #page .dat h6.active,.rooms h6.active{color:#FFF;}  .icon::after {display: inline-block;font-style: normal;font-variant: normal;text-rendering: auto;-webkit-font-smoothing: antialiased;}  .accordion::after {font-family: "Font Awesome 6 Free"; font-weight: 900; content: "\f107";float: right;margin-left: 5px}  .active:after {content: "\f106";color:#FFF;}  .panel {padding: 0 10px;max-height: 0;overflow: hidden;transition: max-height 0.2s ease-out;}  .active + .panel {max-height: 100% !important; transition: max-height 0.2s;}#page .dat .tab{padding:10px;} .form_table .form_row .form_cell{display: block;}   .modal_iframe{height: 100%; width:100%;max-width: 100%; }  .modal_iframe form{padding: 5px;}  .modal_iframe .header {font-size: 14px;}  .modal_iframe .bigicon {font-size: 8px;}  .public_mobil {display: block;}  .pbanner{display:none;}  footer #footer_content .i {padding-left: 10px;width: 95%;}  footer #footer_content .m {padding: 20px 0 0 10px;width: 95%;}  footer #footer_content .d {padding: 20px 0 0 10px;width: 95%;}  .mbtn_promo{background: #53ff2d !important;color:#FFF!important;}  .mparpadeo {animation-name: parpadeo;animation-duration: 1s;animation-timing-function: linear;animation-iteration-count: infinite;-webkit-animation-name:parpadeo;-webkit-animation-duration: 1s;-webkit-animation-timing-function: linear;-webkit-animation-iteration-count: infinite;}.certificacion_imagen{width: 40%}  .contenedor_logos{grid-template-columns: 1fr 1fr;} .contenedor_logos .text1{height: 90px} .mbtn_tienda{background: #008e14!important;color: #FFF!important;} .texto_tienda{margin-top: 0;padding-top: 0}  .opc2 #telefonos{margin-left: 0}  footer #footer_content .flogo img {height: 33px;} .lista-rubros-2 img{height: 219px} .tamama img{height: 166px}
            #telefonos .c_llamar .fa-whatsapp{padding: 5px 0px;
                margin-right: 10px;  border-radius: 50%;
                color: #4285f4;
                font-size: 20px; font-weight: bold}
            .map_goo .lleg{background: #2ecc71;border: 1px solid #2ecc71;border-radius: 3px;color: #FFF!important;display: block;padding: 5px;width: 85px;}
            .map_goo i.fas.fa-location-arrow{font-weight: 500;rotate: 314deg}
            .empresa #address a{display: inline!important;}


        }
        @media screen and (max-width: 350px) {  header #LogoOptions #logo_betm img{max-height: 20px }  header #LogoOptions #logo_img img{max-height: 20px}  }
    </style>
    <script src="/js/jquery-3.5.1.min.js"></script>
    <?php if(isset($guia) and $guia == "g007"){
        $habHotel = false;
        if(isset($rsociales[9]) and $rsociales[9]!="-" AND strpos($rsociales[9], 'booking.com') !== false) {
            $habHotel = true;
            ?>
            <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.min.css"/ >
            <script src="/js/jquery.datetimepicker.full.min.js"></script>
            <style>
                .empresa #reservas{font-size:15px;display:block;margin: 25px;}
                .empresa #reservas .res_cell{display:inline-block;margin: 0 10px;vertical-align: top;}
                .empresa #reservas .res_cell input{margin:3px 0;padding:7px;width: 80%; font-size:15px;}
                .empresa #reservas .res_cell .cdate {position: relative;}
                .empresa #reservas .res_cell .cdate .fa-calendar {color: #333;top: 3px;right: 10px;position: absolute;}
                .empresa #reservas .fdate{width: 15%;}
                .empresa #reservas .fnoche{text-align:center;width: 7%;}
                .empresa #reservas .fnoche .icon_noche{background: transparent url("/imagenes/icon_cal_book.png") no-repeat top center;background-size: 45px 35px;color:#000;display: block;padding: 10px;font-family:Tahoma, Geneva, sans-serif;font-size: 16px;font-weight: bold;font-style: normal;}
                .empresa #reservas .fselect{width: 9%; font-size:15px;}
                .empresa #reservas .fselect select{padding:7px;margin:3px 0;width: 100%; font-size:15px;}
                .empresa #reservas .fbtn{margin-right: 0}
                .empresa #reservas .fbtn button{background:#10a002;border:1px solid #156b0c;color:#FFF;cursor:pointer;font-size:15px;padding: 7px 5px;margin-top: 3px;width: 100%;}
                .empresa #reservas .fbtn button:hover{background:#156b0c;}
                @media screen and (max-width: 980px) {  .empresa #reservas{margin: 25px 10px;}  .empresa #reservas .res_cell{margin: 0 10px;}  }
                @media screen and (max-width: 650px) {  .empresa #reservas .fdate{width: 35%;}  .empresa #reservas .fnoche{margin-right:0;width: 10%;}  .empresa #reservas .fnoche .icon_noche{background-size: 40px 35px;padding: 10px;}  .empresa #reservas .fselect{margin-top: 10px;width: 17%;}  .empresa #reservas .fbtn{margin-top: 10px;}  }
                @media screen and (max-width: 500px) {  .empresa #reservas .fdate{width: 33%;}  .empresa #reservas .fnoche{margin-right:0;width: 10%;}  .empresa #reservas .fnoche .icon_noche{background-size: 40px 35px;}  .empresa #reservas .fselect{width: 25%;}  .empresa #reservas .fbtn{width: 93%;}  }
            </style>
        <?php }
    } ?>
    <script async src="https://platform-api.sharethis.com/js/sharethis.js#property=60393594317d2200110fb52d&product=sop"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-8811081-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-8811081-1');
    </script>
</head>
<body>
<div id="header">
    <header>
        <div id="LogoOptions">
            <div id="logo_betm">
                <a href="/" title="Boliviaentusmanos"><img src="/imagenes/<?php if($guia == "g007"){
                    echo "betm-logo-industria.png";
                }?><?php  if($guia == "amarillas"){
                    echo "betm-logo-industria.png";
                }?><?php  if($guia == "g006"){
                        echo "betm-logo-industria.png";
                    }?><?php  if($guia == "g010"){
                        echo "betm-logo-industria.png";
                    }?><?php  if($guia == "g011"){
                        echo "betm-logo-industria.png";
                    }?>" alt="boliviaentusmanos.com"></a>
            </div>
                <div id="logo_img">
                    <img src="/imagenes/<?=$guia == "g020"  ? "nlogo":"plogo"?>.png" title="Empresa boliviaentusmanos.com" alt="boliviaentusmanos.com">
                </div>
            <div class="menu-nav">
                <button type="button" class="navbar-toggle collapsed js-offcanvas-btn btn_menu">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="hiraku-open-btn-line"></span>
                </button>
            </div>
            <div class="clear"></div>
        </div>
    </header>
</div>
<?php if($tipo==8 && $navegador=="PC"){?>
        <div id="wrapper">
             <div class="megamenu_container megamenu_dark_bar megamenu_light">
        <ul class="megamenu">
            <li>
                <div id="logo8">
                    <div class="logo_betm8">
                        <a href="/">
                            <div class="log_mar">
                                <img src="https://www.boliviaentusmanos.com/imagenes/betm-logo-industria.png" alt="Logo BETM">
                            </div>
                        </a>
                    </div>
                    <div class="logo_img">
                        <img src="https://www.boliviaentusmanos.com/imagenes/plogo.png" alt="Logo Boliviaentusmanos">
                    </div>
                    <div class="clear"></div>
                </div>
            </li>
            <li class="megamenu_right"><a href="#" class="megamenu_drop" ><i class="fas fa-align-justify"></i></a>
                <div class="dropdown_2columns dropdown_right dropdown_container droplast_right">
                    <ul class="dropdown_flyout">
                        <li><a href="/">PORTADA</a></li>
                        <li><a href="/amarillas/">AMARILLAS</a></li>
                        <li><a href="/hoteles-bolivia/">HOTELES</a></li>
                        <li><a href="/restaurantes-gastronomia/ ">RESTAURANTES</a></li>
                        <li class="dropdown_parent"><a href="/turismo/">TURISMO<i class="fas fa-angle-right"></i></a>
                            <ul class="dropdown_flyout_level">
                                <li><a href="/turismo/atractivos-departamento/la-paz.html">Turismo en La Paz</a></li>
                                <li><a href="/turismo/atractivos-departamento/cochabamba.html">Turismo en Cochabamba</a></li>
                                <li><a href="/turismo/atractivos-departamento/santa-cruz.html">Turismo en Santa Cruz</a></li>
                                <li><a href="/turismo/atractivos-departamento/chuquisaca.html">Turismo en Chuquisaca</a></li>
                                <li><a href="/turismo/atractivos-departamento/potosi.html">Turismo en Potosí</a></li>
                                <li><a href="/turismo/atractivos-departamento/oruro.html">Turismo en Oruro</a></li>
                                <li><a href="/turismo/atractivos-departamento/tarija.html">Turismo en Tarija</a></li>
                            </ul>
                        </li>
                        <li><a href="/guiamedica/">GUÍA MÉDICA</a></li>
                        <li><a href="/bolivia-industrias/">INDUSTRIAS</a></li>
                        <li><a href="/tiendas-online-delivery/">TIENDAS ONLINE</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
        </div>
<?php } ?>

<?php if($tipo!=8){ ?>
<div id="nav"> <?php $mamarillas = true; include_once(ROOT1."include/menu4.inc.php"); ?> </div>
<div id="search_content">
    <div id="search">
        <form action="/amarillas/search/buscador.php" method="get" enctype="application/x-www-form-urlencoded" id="form_buscar">
            <input name="query" id="bquery" type="text" placeholder="Buscar servicios / productos / empresas">
            <!--            <span id="btn_search1"><i class="fa fa-search"></i></span>-->
            <select name="ciudad" id="ciudad">
                <option value="bolivia" selected>Todo Bolivia</option>
                <option value="lapaz">La Paz</option>
                <option value="cochabamba">Cochabamba</option>
                <option value="santacruz">Santa Cruz</option>
                <option value="oruro">Oruro</option>
                <option value="tarija">Tarija</option>
                <option value="pando">Pando</option>
                <option value="beni">Beni</option>
                <option value="chuquisaca">Chuquisaca</option>
                <option value="potosi">Potosí</option>
            </select>
            <button type="submit"><i class="fas fa-search"></i></button>
            <div class="clear"></div>
        </form>
    </div>
</div>
<?php } ?>
<div itemscope itemtype="https://schema.org/LocalBusiness">
    <?php if($tipo==7){
        if($navegador=="MOBILE"){?>
            <div class="banner">
                <?php  $aux=explode(';', $auspiciador_cel);$aux2=$aux[0];$ausp=$empresaDirectorio->getAuspiciadores($aux2);
                $row=$ausp[0]?>
                <div class="ban-1">
                    <a href="<?=$row['enlace']?>"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row['logo']?>" class="lazyload" title="<?=$row['descripcion']?>" alt="<?=$row['descripcion']?>"></a>
                </div>
            </div>
        <?php }else{?>
            <div class="banner">
                <?php  $aux=explode(';', $auspiciador);$aux2=$aux[0];$ausp=$empresaDirectorio->getAuspiciadores($aux2);
                $row=$ausp[0]?>
                <div id="wrapper">
                    <div class="ban-1">
                        <a href="<?=$row['enlace']?>"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row['logo']?>" class="lazyload" title="<?=$row['descripcion']?>" alt="<?=$row['descripcion']?>"></a>
                    </div>
                </div>
            </div>
        <?php }}?>
    <?php if($tipo!=8){ ?>
    <div class="empresa">
        <div class="hide">
            <a href="https://www.boliviaentusmanos.com/amarillas/businesscard/<?=$paginaBusiness?>.html" itemprop="url">https://www.boliviaentusmanos.com/amarillas/businesscard/<?=$paginaBusiness?>.html</a>
        </div>
        <div id="cont">
            <div class="logo"><?php if(isset($logo)) echo '<img src="/amarillas/blogos/'.$logo.'" title="'."logo ".$nombre_edit.'" alt="'."logo ".$nombre_edit.'" width="250" height="100" itemprop="image">'; ?></div>
            <div class="datos">
                <h1 itemprop="name"><?=str_ireplace(' *', ' <i class="fas fa-star" ></i>',$nombre)?></h1>
                <div class="dl">
                    <div class="dir" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                        <?php $nciudad = $nombreciudad == $nombredepto ? '<strong>'.mb_strtoupper($nombredepto, 'UTF-8').'</strong>' : $nombreciudad.', <strong>'.mb_strtoupper($nombredepto, 'UTF-8').'</strong>'; ?>
                        <p id="address"><i class="fas fa-map-marker-alt"></i>
                            <span itemprop="streetAddress">
                                <?php if($navegador=="MOBILE"){ ?>
                                <a href="https://maps.google.com/?q=<?=$clat?>,<?=$clng?>"><?=$direccion?></a>
                                <?php } else { ?>
                                    <?=$direccion?>
                                <?php } ?>
                            </span><br>
                            <span itemprop="addressLocality"><strong><?=$nciudad?></strong></span>
                           </p>
                        <div class="clear"></div>

                        <div id="telefonos">
                        <?php if($tipocel==1){ ?>
                            <?php if(isset($telefono1)){
                                echo $navegador == "MOBILE"? "<a href='tel:".substr($codigotelf, -2,1).$vtel[0]."' class='telf1' style='text-decoration: none' ><div class='c_llamar'><i class='fas fa-phone'></i><br></div> <p class='phone' itemprop='telephone'>".$codigotelf." ".$vtel[0]."</p></a><div class='clear'></div>": "<p class='phone' itemprop='telephone'><i class='fas fa-phone'></i> ".$codigotelf." ".$vtel[0]."</p>";
                            }?>
                           <?php if(isset($whatsapp)){
                                //echo $navegador == "MOBILE"? "<a href='tel:$vcel[0]' class='cel1' style='text-decoration: none' itemprop='telephone'><div class='c_llamar'><i class='fas fa-mobile-alt'></i><br></div><p class='cell'> (591) ".$vcel[0]."</p></a>": "<p class='cell' itemprop='telephone'><i class='fas fa-mobile-alt'></i> (591) ".$vcel[0]."</p>";
                                echo $navegador == "MOBILE"? "
<a href='whatsapp://send?text=Hola&phone=+591<?=$vcel[0]?>' class='what1' style='text-decoration: none' itemprop='telephone'>
<div class='c_llamar'>
<i style='font-weight: normal!important;font-size: 25px!important; padding:0!important;' class='fab fa-whatsapp'></i>
<br>
</div>
<p class='what1'> (591) ".$vcel[0]."</p>
</a>": "
<p class='what1' itemprop='telephone'><i style='font-weight: normal!important;font-size: 25px!important; padding:0!important;' class='fab fa-whatsapp'></i> (591) ".$vcel[0]."</p>";
                            }

                            ?>
                            <?php } ?>

                        <?php if($tipocel==2){ ?>
                            <?php
                            if(isset($celular)){
                                echo $navegador == "MOBILE"? "<a href='tel:$vcel[0]' class='cel1' style='text-decoration: none' itemprop='telephone'><div class='c_llamar'><i class='fas fa-mobile-alt'></i><br></div><p class='cell'> (591) ".$vcel[0]."</p></a><div class='clear'></div>": "<p class='cell' itemprop='telephone'><i class='fas fa-mobile-alt'></i> (591) ".$vcel[0]."</p>";

                                echo $navegador == "MOBILE"? "<a href='tel:$vcel[1]' class='cel1' style='text-decoration: none' itemprop='telephone'><div class='c_llamar'><i class='fas fa-mobile-alt'></i><br></div><p class='cell1'> (591) ".$vcel[1]."</p></a>": "<p class='cell1' itemprop='telephone'><i class='fas fa-mobile-alt'></i> (591) ".$vcel[1]."</p>";
                            }
                            ?>
                        <?php } ?>
                        <?php if($tipocel==3){ ?>
                            <?php
                            if(isset($whatsapp)){
                                echo $navegador == "MOBILE"? "
<a href='whatsapp://send?text=Hola&phone=+591<?=$vcel[0]?>' class='what1' style='text-decoration: none' itemprop='telephone'>
<div class='c_llamar'>
<i style='font-weight: normal!important;font-size: 25px!important;padding:0!important; ' class='fab fa-whatsapp'></i>
<br>
</div>
<p class='what1'> (591) ".$vcel[0]."</p>
</a>": "
<p class='what1' itemprop='telephone'><i style='font-weight: normal!important;font-size: 25px!important; padding:0!important;' class='fab fa-whatsapp'></i> (591) ".$vcel[0]."</p>";

                                echo $navegador == "MOBILE"? "
<a href='whatsapp://send?text=Hola&phone=+591<?=$vcel[1]?>' class='cel1' style='text-decoration: none' itemprop='telephone'>
<div class='c_llamar'>
<i style='font-weight: normal!important;font-size: 25px!important;padding:0!important;' class='fab fa-whatsapp'></i>
<br>
</div>
<p class='what2'> (591) ".$vcel[1]."</p>
</a>": "
<p class='what2' itemprop='telephone'><i style='font-weight: normal!important;font-size: 25px!important;padding:0!important;' class='fab fa-whatsapp'></i> (591) ".$vcel[1]."</p>";
                            }
                            ?>
                        <?php } ?>


                        </div><div class="clear"></div>
                    </div>
                    <?php if($guia == "g007" AND isset ($rsociales[9]) AND $rsociales[9]!="-"){ ?>
                        <div class="opciones">
                            <p class='sitio'><a href='<?=$rsociales[9]?>' target='_blank'><i class='img_sitio'></i>Reserva en el hotel</a></p><a href="<?=$rsociales[9]?>" target="_blank" class='reservar2'>Reservar ahora</a>
                        </div>
                    <?php } ?>
                    <?php if(isset ($rsociales[11]) AND $rsociales[11]!="-" ){ ?>
                        <div class="opciones">
                            <a href="<?=$rsociales[11]?>" target="_blank" class='reservar_cita'>Reservar cita</a>
                        </div>
                    <?php } ?>
                    <?php if($guia == "g006" && isset ($otros_servicios[1]) AND $otros_servicios[1] != "-" ){ ?>
                        <div class="opciones">
                            <a target='_blank' class='rdelivery'><i class="fas fa-motorcycle"></i>   Delivery</a>
                        </div>
                    <?php }?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
<?php }else{?>
        <div id="personal">
            <div class="fondo">
                <?php $imagen=explode(';', $fondo_profesional);
                if($navegador=="PC" || $navegador=="TABLET"){ ?>
                    <img src="https://www.boliviaentusmanos.com/amarillas1/fondos/<?=$imagen[0]?>" alt="Fondo BC-8">
                <?php }else{ ?>
                    <img src="https://www.boliviaentusmanos.com/amarillas1/fondos/<?=$imagen[1]?>" alt="Fondo BC-8">

                <?php } ?>

            </div>
            <div class="empresa">
                <div class="contenedor">
                    <div class="cont_izq">
                        <div class="logo"><?php if(isset($logo)) echo '<img src="/amarillas/blogos/'.$logo.'" title="'."logo ".$nombre_edit.'" alt="'."logo ".$nombre_edit.'" itemprop="image">'; ?></div>
                    </div>
                    <div class="cont_der">
                        <div class="datos">
                            <h1 itemprop="name"><?=str_ireplace(' *', ' <i class="fas fa-star" ></i>',$nombre)?></h1>
                            <div class="info_profesional">
                                <p><?=$especialidad_profesional?></p>
                                <p class="institucion"><?=$institucion?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
   <?php } ?>
    <div class="clear"></div>
    <?php if(isset ($rsociales[11]) AND $rsociales[11]!="-"){
        if($codigoempresa==11922){?>
        <?php if($navegador=="MOBILE"){  ?>
            <div class="empresa cita">
                <div class="banner-cita">
                    <a href="<?=$rsociales[11]?>">
                        <div class="ratio-box fade-box tamcita">
                            <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg"  data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/banners/banner-citas-online-naranja-mobile.jpg" class="lazyload blur-up" title="Banner citas online" alt="Banner citas online">
                        </div>
                    </a>
                </div>
            </div>
        <?php }else{ ?>
            <div class="empresa cita">
                <div class="banner-cita">
                    <a href="<?=$rsociales[11]?>">
                        <div class="ratio-box fade-box tamcita">
                            <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/banners/banner-citas-online-naranja-pc.jpg" class="lazyload blur-up" title="Banner citas online" alt="Banner citas online">
                        </div>
                    </a>
                </div>
            </div>
        <?php }?>
        <?php } else{ ?>
            <?php if($navegador=="MOBILE"){  ?>
                <div class="empresa cita">
                    <div class="banner-cita">
                        <a href="<?=$rsociales[11]?>">
                            <div class="ratio-box fade-box tamcita">
                                <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg"  data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/banners/banner-citas-online-celular.png" class="lazyload blur-up" title="Banner citas online" alt="Banner citas online">
                            </div>
                        </a>
                    </div>
                </div>
            <?php }else{ ?>
                <div class="empresa cita">
                    <div class="banner-cita">
                        <a href="<?=$rsociales[11]?>">
                            <div class="ratio-box fade-box tamcita">
                                <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/banners/banner-citas-online.png" class="lazyload blur-up" title="Banner citas online" alt="Banner citas online">
                            </div>
                        </a>
                    </div>
                </div>
            <?php }?>
        <?php } ?>
    <?php } ?>
    <?php if($habHotel){
        $url_guest = explode("=",$rsociales[9]);
        $apikey = $url_guest[1];
        ?>
        <div class="empresa">
            <div id="reservas">
                <form id="gdlr-hotel-availability" method="post" action="reservas.php" >
                    <div class="res_cell fdate">
                        Check-In:<br>
                        <label class="cdate">
                            <input type="text" readonly="true" id="mindate" class="cinput" value="<?=date("d/m/Y")?>" placeholder="dd/mm/YYYY" />
                            <input type="hidden" name="arrive" id="startDay" value="<?=date("Y-m-d")?>" />
                        </label>
                        <span id="dia_in"><?=$OpDiaArray[date("N")]?></span>
                    </div>
                    <div class="res_cell fdate">
                        Check-Out:<br>
                        <label class="cdate">
                            <input type="text" readonly="true" id="maxdate" class="cinput" value="<?=date("d/m/Y", strtotime($manana))?>" placeholder="dd/mm/YYYY" />
                            <input type="hidden" name="depart" id="gdlr-check-out" value="<?=date("Y-m-d", strtotime($manana))?>" />
                        </label>
                        <span id="dia_out"><?=$OpDiaArray[date("N", strtotime($manana))]?></span>
                    </div>
                    <div class="res_cell fnoche">
                        <br>
                        <i class="icon_noche">1</i>
                        <input type="hidden" id="nrNights" value="1">
                        Noches
                    </div>
                    <div class="res_cell fselect">
                        <label for="gdlr-room-number">Habitaciones:</label> <br>
                        <select name="rooms" id="gdlr-room-number">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                        </select>
                    </div>
                    <div class="res_cell fselect">
                        <label for="nrAdults">Adultos:</label> <br>
                        <select id="nrAdults" name="adult">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                        </select><br>
                    </div>
                    <div class="res_cell fselect">
                        <label for="nrChildren">Niños:</label> <br>
                        <select name="child" id="nrChildren">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                        </select><br>
                    </div>
                    <div class="res_cell fbtn"><br>
                        <input id="APIlang" name="hotel" type="hidden" value="<?=$rsociales[9]?>" />
                        <input name="locale" type="hidden" value="es-ES" />
                        <button type="submit" name="guardar_reserva">Verificar Disponibilidad</button>
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>
    <div id="wrapper">
        <div id="container">
            <div>
                <div id="page">
                    <?php if($tipo!=8){ ?>
                    <?php if($fotos){ ?>
                        <div id="fotos">
                                <div class="slider_fotos">
                                    <a class="slick_controls slick_previous"><i class="fas fa-angle-left"></i></a>
                                    <a class="slick_controls slick_next"><i class="fas fa-angle-right"></i></a>
                                    <div class="your-class">
                                        <?php
                                        $fcont = count($foto)<=5 ? count($foto):5;
                                        for($i = 0; $i<$fcont ; $i++){
                                        $pics = $foto[$i];?>
                                        <div>
                                            <div class="ratio-box fade-box tamslider">
                                                 <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas1/businesscard/imagenes/<?=$pics?>" class="lazyload blur-up" title="<?=$nombre?> <?=$i?>" alt="<?=$nombre?> <?=$i?>">
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                        </div>
                        <div class="hide"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="/amarillas1/businesscard/imagenes/<?=$foto[0]?>" class="lazyload" title="<?=$nombre?>" alt="<?=$nombre?>" itemprop="image"></div>
                    <?php } ?>
                    <?php }else{ ?>
                            <div class="opc2">
                                <div class="datos">
                                    <div class="dl">
                                        <div class="dir" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                            <?php $nciudad = $nombreciudad == $nombredepto ? '<strong>'.mb_strtoupper($nombredepto, 'UTF-8').'</strong>' : $nombreciudad.', <strong>'.mb_strtoupper($nombredepto, 'UTF-8').'</strong>'; ?>
                                            <p id="address"><i class="fas fa-map-marker-alt"></i>
                                                <span itemprop="streetAddress">
                                                      <?php if($navegador=="MOBILE"){ ?>
                                                          <a href="https://maps.google.com/?q=<?=$clat?>,<?=$clng?>"><?=$direccion?></a>
                                                      <?php } else { ?>
                                                          <?=$direccion?>
                                                      <?php } ?>
                                                </span><br>
                                                <span itemprop="addressLocality"><strong><?=$nciudad?></strong></span>
                                            </p>
                                            <div class="clear"></div>
                                            <div id="telefonos">


                                                <?php if($tipocel==1){ ?>
                                                    <?php if(isset($telefono1)){
                                                        echo $navegador == "MOBILE"? "<a href='tel:".substr($codigotelf, -2,1).$vtel[0]."' class='telf1' style='text-decoration: none' ><div class='c_llamar'><i class='fas fa-phone'></i><br></div> <p class='phone' itemprop='telephone'>".$codigotelf." ".$vtel[0]."</p></a> <div class='clear'></div>": "<p class='phone' itemprop='telephone'><i class='fas fa-phone'></i> ".$codigotelf." ".$vtel[0]."</p>";
                                                    }?>

                                                    <?php if(isset($whatsapp)){
                                                        //echo $navegador == "MOBILE"? "<a href='tel:$vcel[0]' class='cel1' style='text-decoration: none' itemprop='telephone'><div class='c_llamar'><i class='fas fa-mobile-alt'></i><br></div><p class='cell'> (591) ".$vcel[0]."</p></a>": "<p class='cell' itemprop='telephone'><i class='fas fa-mobile-alt'></i> (591) ".$vcel[0]."</p>";
                                                        echo $navegador == "MOBILE"? "
<a href='whatsapp://send?text=Hola&phone=+591<?=$vcel[0]?>' class='what1' style='text-decoration: none' itemprop='telephone'>
<div class='c_llamar'>
<i style='font-weight: normal!important;font-size: 25px!important; padding:0!important;' class='fab fa-whatsapp'></i>
<br>
</div>
<p class='what1'> (591) ".$vcel[0]."</p>
</a>": "
<p class='what1' itemprop='telephone'><i style='font-weight: normal!important;font-size: 25px!important; padding:0!important;' class='fab fa-whatsapp'></i> (591) ".$vcel[0]."</p>";
                                                    }

                                                    ?>
                                                <?php } ?>

                                                <?php if($tipocel==2){ ?>
                                                    <?php
                                                    if(isset($celular)){
                                                        echo $navegador == "MOBILE"? "<a href='tel:$vcel[0]' class='cel1' style='text-decoration: none' itemprop='telephone'><div class='c_llamar'><i class='fas fa-mobile-alt'></i><br></div><p class='cell'> (591) ".$vcel[0]."</p></a><div class='clear'></div>": "<p class='cell' itemprop='telephone'><i class='fas fa-mobile-alt'></i> (591) ".$vcel[0]."</p>";

                                                        echo $navegador == "MOBILE"? "<a href='tel:$vcel[1]' class='cel1' style='text-decoration: none' itemprop='telephone'><div class='c_llamar'><i class='fas fa-mobile-alt'></i><br></div><p class='cell1'> (591) ".$vcel[1]."</p></a>": "<p class='cell1' itemprop='telephone'><i class='fas fa-mobile-alt'></i> (591) ".$vcel[1]."</p>";
                                                    }
                                                    ?>
                                                <?php } ?>
                                                <?php if($tipocel==3){ ?>
                                                    <?php
                                                    if(isset($whatsapp)){
                                                        echo $navegador == "MOBILE"? "
<a href='whatsapp://send?text=Hola&phone=+591<?=$vcel[0]?>' class='what1' style='text-decoration: none' itemprop='telephone'>
<div class='c_llamar'>
<i style='font-weight: normal!important;font-size: 25px!important;padding:0!important; ' class='fab fa-whatsapp'></i>
<br>
</div>
<p class='what1'> (591) ".$vcel[0]."</p>
</a><div class='clear'></div>": "
<p class='what1' itemprop='telephone'><i style='font-weight: normal!important;font-size: 25px!important; padding:0!important;' class='fab fa-whatsapp'></i> (591) ".$vcel[0]."</p>";

                                                        echo $navegador == "MOBILE"? "
<a href='whatsapp://send?text=Hola&phone=+591<?=$vcel[1]?>' class='cel1' style='text-decoration: none' itemprop='telephone'>
<div class='c_llamar'>
<i style='font-weight: normal!important;font-size: 25px!important;padding:0!important;' class='fab fa-whatsapp'></i>
<br>
</div>
<p class='what2'> (591) ".$vcel[1]."</p>
</a>": "
<p class='what2' itemprop='telephone'><i style='font-weight: normal!important;font-size: 25px!important;padding:0!important;' class='fab fa-whatsapp'></i> (591) ".$vcel[1]."</p>";
                                                    }
                                                    ?>
                                                <?php } ?>
                                            </div>
                                            <div class="clear"></div>
                                            <div class="clear"></div>
                                        </div>
                                        <?php if($guia == "g007" AND isset ($rsociales[9]) AND $rsociales[9]!="-"){ ?>
                                            <div class="opciones">
                                                <p class='sitio'><a href='<?=$rsociales[9]?>' target='_blank'><i class='img_sitio'></i>Reserva en el hotel</a></p><a href="<?=$rsociales[9]?>" target="_blank" class='reservar2'>Reservar ahora</a>
                                            </div>
                                        <?php } ?>
                                        <?php if(isset ($rsociales[11]) AND $rsociales[11]!="-"){ ?>
                                            <div class="opciones">
                                                <a href="<?=$rsociales[11]?>" target="_blank" class='reservar_cita'>Reservar cita</a>
                                            </div>
                                        <?php } ?>
                                        <?php if(isset ($rsociales[12]) AND $rsociales[12]!="-" ){ ?>
                                            <div class="opciones">
                                                <a href="<?=$rsociales[12]?>" target="_blank" class='reservar_cita'>Reservar cita</a>
                                            </div>
                                        <?php } ?>
                                        <?php if($guia == "g006" && isset ($otros_servicios[1]) && $otros_servicios[1] != "-" && $otros_servicios[1] != "delivery"){ ?>
                                            <div class="opciones">
                                                <a href='<?=$otros_servicios[1]?>' target='_blank' class='rdelivery'>Delivery</a>
                                            </div>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>

                  <?php  } ?>
                    <div class="dat tab_act" itemprop="description">
                        <h2 class="tab act"><?= replaceTextoBusinessRubros($actividades, $descrubros);?></h2>
                    </div>
                    <div class="menu_content">
                        <button id="menu_1" onclick="mostrarSeleccion(1)" class="btn_menu btn_link active">Productos / Servicios</button>
                        <?php if(isset($comodidades) && isset($mas_servicios)){ ?>
                            <button id="menu_10" onclick="mostrarSeleccion(10)" class="btn_menu btn_link">Servicios más solicitados</button>
                        <?php }
                        if(isset($catalogotab) && count($catalogotab) > 0){ ?>
                            <?php if($guia=='g006'){ ?>
                                <button id="menu_3" onclick="mostrarSeleccion(3)" class="btn_menu btn_link btn_catalogo parpadea">Menú</button>
                            <?php }else{ ?>
                                <button id="menu_3" onclick="mostrarSeleccion(3)" class="btn_menu btn_link btn_catalogo parpadea">Catálogo</button>
                            <?php } ?>
                        <?php }?>
                       <?php if(isset($tienda)){ ?>
                        <button id="menu_12" onclick="mostrarSeleccion(12)" class="btn_menu2 btn_link btn_tienda parpadea">Tienda online</button>
                        <?php } else {
                            if(isset($tienda_link)){?>
                                <a id="menu_12" href="<?=$tienda_link?>" onclick="mostrarSeleccion(12)" class="btn_menu2 btn_link btn_tienda parpadea">Tienda online</a>

                            <?php }}?>
                        <?php if(isset($video)){ ?>
                            <button id="menu_4" onclick="mostrarSeleccion(4)" class="btn_menu btn_link">Videos</button>
                        <?php }
                        if(isset($promos) && count($promos) > 0){ ?>
                            <button id="menu_6" onclick="mostrarSeleccion(6)" class="btn_menu btn_link btn_promo parpadea">Ofertas</button>
                        <?php }
                        if(isset($portafolio)){ ?>
                            <button id="menu_6" onclick="mostrarSeleccion(11)" class="btn_menu btn_link">Portafolio</button>
                        <?php }
                        if(count($habitaciones)){ ?>
                            <button id="menu_5" onclick="mostrarSeleccion(5)" class="btn_menu btn_link">Habitaciones</button>
                        <?php }
                        if(count($servicios_hotel)){ ?>
                            <button id="menu_2" onclick="mostrarSeleccion(2)" class="btn_menu btn_link">Más Servicios</button>
                        <?php }
                        if(isset($especialidades)){ ?>
                            <button id="menu_8" onclick="mostrarSeleccion(8)" class="btn_menu btn_link">Especialidades</button>
                        <?php }?>
                        <?php
                        if(isset($acerca_de_mi)){ ?>
                            <button id="menu_13" onclick="mostrarSeleccion(13)" class="btn_menu btn_link">Acerca de mí</button>
                        <?php } ?>
                        <?php if(isset($certificados)){ ?>
                            <button id="menu_7" onclick="mostrarSeleccion(7)" class="btn_menu btn_link">Certificaciones</button>
                        <?php }?>
                        <?php if($tipo==8){ ?>
                            <?php if(isset($foto)){ ?>
                                <button id="menu_9" onclick="mostrarSeleccion(9)" class="btn_menu btn_link">Galería</button>
                            <?php } ?>

                        <?php }else{?>
                            <?php if(count($foto) > 5){ ?>
                                <button id="menu_9" onclick="mostrarSeleccion(9)" class="btn_menu btn_link">Galería</button>
                            <?php } ?>
                       <?php } ?>

                        <?php if(isset($reserva_cita_doctor[11]) and $reserva_cita_doctor[11] != "-") { ?>
                            <button id="menu_14" onclick="mostrarSeleccion(14)" class="btn_menu btn_link">Reservar cita</button>
                        <?php } ?>
                        <div class="clear"></div>
                    </div>
                    <?php if(isset($comodidades) && isset($mas_servicios)): ?>
                        <div id="content_10" class="content_hide">
                            <section class="dat" itemprop="description">
                                <h2 class="accordion">Servicios más solicitados</h2>
                                <div class="panel">
                                    <div class="serv">
                                        <?=$comodidades?>
                                    </div>
                                    <div class="clear"></div>
                                    <div class=''>
                                        <?=replaceTextoBusinessRubros($mas_servicios, $descrubros);?>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($productos)):?>
                        <div id="content_1" class="content_show">
                            <section class="dat" itemprop="description">
                                <h3 class="icon accordion">Productos / Servicios</h3>
                                <div class='panel'>
                                    <div class="tab">
                                        <?=replaceTextoBusinessRubros($productos, $descrubros);?>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($catalogotab) && count($catalogotab) > 0){?>
                        <div id="content_3" class="content_hide">
                            <section class="dat" itemprop="description">
                                <?php if($guia=='g006'){ ?>
                                    <h3 class="accordion mparpadeo mbtn_catalogo">Menú</h3>
                                <?php }else{?>
                                    <h3 class="accordion mparpadeo mbtn_catalogo">Catálogo</h3>
                                <?php }?>
                                <div class="panel">
                                    <div class="tab">

                                        <?php /*if($catalogo_pdf){?>
                                            <div class="imgcatalogo" style="background:url(../pdf/logo/<?=$catalogo_pdf_img?>) no-repeat">
                                                <a href="../pdf/doc/<?=$catalogo_pdf?>" target="_blank">
                                                    <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="/amarillas1/imagenes/catalogo-img-pdf.png" class="lazyload" title="Catálogo" alt="Catálogo">
                                                </a>
                                            </div>
                                        <?php } */?>

                                        <div class="contenedor_logoscat">
                                            <?php
                                            foreach($catalogotab as $row){?>
                                                <div class="box">
                                                    <a href="<?=$row['web'] ?>">
                                                        <div class="cont_img">
                                                            <img src="https://www.boliviaentusmanos.com/amarillas/pdf/logo/<?=$row['imagen']?>" alt="">
                                                        </div>
                                                        <div class="cont_text">
                                                            <p class="text1"><?=$row['nombre']?></p>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php  } ?>
                                        </div>

                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php } ?>

                    <?php if(isset($tienda)){ ?>
                        <div id="content_12" class="content_hide">
                            <section class="dat" itemprop="description">
                                <h3 class="accordion mparpadeo mbtn_tienda titulo_tienda">Tienda Online</h3>
                                <div class="panel">
                                    <div class="tab">

                                            <p class="texto_tienda">Esta <b>TIENDA ONLINE</b> pertenece a <b><?=str_ireplace(' *', ' <i class="fas fa-star" ></i>',$nombre)?></b>. <br>
                                                Todos los productos solicitados son atendidos directamente por <b><?=str_ireplace(' *', ' <i class="fas fa-star" ></i>',$nombre)?></b>. Cualquier consulta que tenga pónganse en contacto con los teléfonos que se encuentran en <b>DATOS DE CONTACTO</b>.</p>
                                            <div class="tienda_prod">
                                                <?=$tienda; ?>
                                            </div>
                                            <div class="clear"></div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="clear"></div>
                    <?php } ?>
                    <?php if( $tienda==null && isset($tienda_link)){ ?>
                        <a href="<?=$tienda_link; ?>" style="text-decoration: none" >
                            <div id="content_12" class="content_hide">
                                <section class="dat" itemprop="description">
                                    <h3 class="accordion mparpadeo mbtn_tienda titulo_tienda">Tienda Online</h3>
                                    <div class="panel">
                                        <div class="tab">
                                            <p style="color:#008e14">Ver Tienda:</p>
                                            <div class="tienda_link">
                                                <a href="<?=$tienda_link; ?>" target="_blank"><?=$tienda_link; ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </a>

                    <?php } ?>

                    <?php if(isset($especialidades)){ ?>
                        <div id="content_8" class="content_hide">
                            <section class="dat" itemprop="description">
                                <h3 class="accordion">Especialidades</h3>
                                <div class="panel">
                                    <div class="tab">
                                        <?=replaceTextoBusinessRubros($especialidades, $descrubros); ?>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php } ?>
                    <?php if(isset($certificados)){?>
                        <div id="content_7" class="content_hide">
                            <section class="dat" itemprop="description">
                                <h3 class="accordion">Certificaciones / Afiliaciones</h3>
                                <div class="panel">
                                    <div class="tab">
                                        <?=$certificados ?>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php } ?>

                    <?php if(isset($reserva_cita_doctor[11]) and $reserva_cita_doctor[11] != "-"){ ?>
                        <div id="content_14" class="content_hide">
                            <section class="dat" itemprop="description">
                                <h3 class="accordion">Reservar cita</h3>
                                <div class="panel">
                                    <div class="tab">
                                        <p>Para reservar su cita por favor haga click en el siguiente enlace:</p>

                                        <a href="<?=$reserva_cita_doctor[11]?>"><?=$reserva_cita_doctor[11]?></a>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php } ?>
                    <?php if(isset($acerca_de_mi)){ ?>
                        <div id="content_13" class="content_hide">
                            <section class="dat" itemprop="description">
                                <h3 class="accordion">Acerca de mí</h3>
                                <div class="panel">
                                    <div class="tab">
                                        <?=$acerca_de_mi; ?>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php } ?>
                    <?php if(isset($portafolio)){?>
                        <div id="content_11" class="content_hide">
                            <section class="dat" itemprop="description">
                                <h3 class="accordion">Portafolio</h3>
                                <div class="panel">
                                    <div class="tab">
                                        <?=$portafolio ?>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php } ?>
                    <?php if(count($habitaciones)){ ?>
                        <div id="content_5" class="content_hide">
                            <section class="dat">
                                <h3 class="accordion">Habitaciones</h3>
                                <div class="panel">
                                    <?php $i=1;
                                    foreach ($habitaciones as $row){
                                        switch ($i%3){
                                            case 1:
                                                $class = "i";
                                                break;
                                            case 2:
                                                $class = "m";
                                                break;
                                            case 0:
                                                $class = "d";
                                                break;
                                            default:
                                                $class = "";
                                                break;
                                        } $i++;
                                        ?>
                                        <div class="content_room">
                                            <div class="content_row <?=$class?>">
                                                <div class="img_room">
                                                    <a href="/amarillas1/businesscard/imagenes/<?=$row["imagen"]?>" data-fancybox="gallery_hab">
                                                        <div class="ratio-box fade-box tamhab">
                                                            <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="/amarillas1/businesscard/imagenes/<?=$row["imagen"]?>"class="lazyload blur-up" title="<?=$row['nombre']?>" alt="<?=$row['nombre']?>">
                                                        </div>
                                                    </a>
                                                </div>
                                                <p><?=$row['nombre']?></p>
                                                <?php /*?>
                                                <a href="<?=$rsociales[9]?>" class="btn_book" target="_blank">Reservar</a>
                                                <?php */ ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="clear"></div>
                                </div>
                            </section>
                        </div>
                    <?php } ?>
                    <?php if(count($servicios_hotel)){ ?>
                        <div id="content_2" class="content_hide">
                            <section class="dat">
                                <h3 class="accordion">Más Servicios</h3>
                                <div class="panel">
                                    <?php
                                    foreach ($servicios_hotel as $row){ ?>
                                        <div class="servicio">
                                            <div class="servicio_img">
                                                <div class="ratio-box fade-box tamimg">
                                                    <div class="ratio-box fade-box tammas">
                                                        <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="/amarillas1/businesscard/imagenes/<?=$row['imagen']?>" class="lazyload blur-up" title="<?=$row['nombre']?>" alt="<?=$row['nombre']?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="servicio_table">
                                                <div class="servicio_row">
                                                    <div class="servicio_cell der">
                                                        <p><?=$row['nombre']?></p>
                                                        <?=$row['descripcion']?>
                                                    </div>
                                                    <?php if(!empty($row['atencion'])){ ?>
                                                        <div class="servicio_cell izq">
                                                            <p>Horarios de Atención</p>
                                                            <?=$row['atencion']?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <?php
                                                $relacionado = $habModel->getServiciosEmpresaRelacion($row['id']);
                                                if(count($relacionado) > 0){
                                                    foreach ($relacionado as $row1){ ?>
                                                        <div class="servicio_row">
                                                            <div class="servicio_cell der">
                                                                <p style="margin-top: 20px;"><?=$row1['nombre']?></p>
                                                                <?=$row1['descripcion']?>
                                                            </div>
                                                            <?php if(!empty($row1['atencion'])){ ?>
                                                                <div class="servicio_cell izq">
                                                                    <p>Horarios de Atención</p>
                                                                    <?=$row1['atencion']?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php }
                                                } ?>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </section>
                        </div>
                    <?php } ?>

                    <?php if(isset($video)){?>
                        <div id="content_4" class="content_hide">
                            <section class="dat" itemprop="description">
                                <h3 class="accordion">Videos</h3>
                                <div class="panel">
                                    <div class="tab">
                                        <?php if($video){
                                            $vids = explode(";", $video);
                                            foreach ($vids as $row){ ?>
                                                <div class="imgvideo" style="background:url(https://i.ytimg.com/vi/<?=$row ?>/hqdefault.jpg) no-repeat;background-size: 230px 140px; float:left;margin-right: 15px;margin-bottom: 15px;">
                                                    <a class="coments_in fancybox.iframe" href="https://www.youtube.com/embed/<?=$row?>?autoplay=1" >
                                                        <img src="/amarillas1/imagenes/video.png" title="Video" alt="Video" >
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php } ?>
                    <?php if(isset($promos) && count($promos) > 0){?>
                        <div id="content_6" class="content_hide">
                            <section class="dat" itemprop="description">
                                <h3 class="accordion mparpadeo mbtn_promo">Ofertas</h3>
                                <div class="panel">
                                    <div class="tab">
                                        <ul class="promoss">
                                            <?php
                                            for($i = 0; $i < count($promos); $i++ ){
                                                $row = $promos[$i];?>
                                                <li>
                                                    <div class="promo_cont <?=$classg?> <?=$classp?>">
                                                        <a href="/amarillas/businesscard/promociones/<?=$row["foto"] ?>" class="promo_imagen" data-fancybox="gallery_promo"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="/amarillas/businesscard/promociones/<?=$row["foto"] ?>" class="lazyload" title="Promo: <?=$i+1?>" alt="Promo: <?=$i+1?>"></a>
                                                        <div class="promos_data">
                                                            <p class="promo_costo"><?=!empty($row['costo']) ? $row['costo']:"&nbsp;"?></p>
                                                            <div class="promo_content">
                                                                <p class="promo_titulo">
                                                                    <?php if(!empty($row['enlace'])){ ?>
                                                                        <a href="<?=$row['enlace']?>" target="_blank"><?=$row['titulo']?></a>
                                                                    <?php }else{ ?>
                                                                        <?=$row['titulo']?>
                                                                    <?php } ?>
                                                                </p>
                                                                <?php if($row['tipo'] == "fecha"){ ?>
                                                                    <p class="promo_fecha"><span class="promo_finicio">Desde: <span class="promo_date"><?= date("d", strtotime($row['inicio'])) ."/". $OpcMes[date("n", strtotime($row['inicio']))] ?></span></span> <span class="promo_ffin">Hasta: <span class="promo_date"><?= date("d", strtotime($row['fin'])) ."/". $OpcMes[date("n", strtotime($row['fin']))] ?></span></span></p>
                                                                <?php } ?>
                                                                <?php if(!empty($row['descripcion'])){ ?>
                                                                    <p class="promo_descripcion"><?=$row['descripcion']?></p>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php } ?>

                    <?php if($tipo==8 && isset($foto)){ ?>
                        <div id="content_9" class="content_hide" itemscope itemtype="https://schema.org/ImageObject">
                            <section class="dat" itemprop="description">
                                <h3 class="accordion">Galería</h3>
                                <div class="panel">
                                    <div class="tab">
                                        <div id="content_galeria">
                                            <ul class="catalogo">
                                                <?php
                                                $j = 0;
                                                for($i = 0; $i < count($foto); $i++ ){
                                                    $pics = $foto[$i];
                                                    switch ($i % 3){
                                                        case 1:
                                                            $classg = "der";
                                                            break;
                                                        case 2:
                                                            $classg = "izq";
                                                            break;
                                                        case 0:
                                                            $classg = "med";
                                                            break;
                                                        default:
                                                            $classg = "";
                                                            break;
                                                    }
                                                    switch ($j%2==0){
                                                        case 1:
                                                            $classp = "mizq";
                                                            break;
                                                        case 0:
                                                            $classp = "mder";
                                                            break;
                                                        default:
                                                            $classp = "";
                                                            break;
                                                    } $j++;
                                                    ?>
                                                    <li>
                                                        <a href="/amarillas1/businesscard/imagenes/<?=$pics?>"  class="<?=$classg?> <?=$classp?>" data-fancybox="gallery_foto">
                                                            <div class="ratio-box fade-box tamgal">
                                                                <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="/amarillas1/businesscard/imagenes/<?=$pics?>" class="lazyload blur-up" title="<?=$nombre?> <?=$i?>" alt="<?=$nombre?> <?=$i?>">
                                                            </div>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                    <?php } else{?>
                        <?php if(count($foto) > 5){?>
                            <div id="content_9" class="content_hide" itemscope itemtype="https://schema.org/ImageObject">
                                <section class="dat" itemprop="description">
                                    <h3 class="accordion">Galería</h3>
                                    <div class="panel">
                                        <div class="tab">
                                            <div id="content_galeria">
                                                <ul class="catalogo">
                                                    <?php
                                                    $j = 0;
                                                    for($i = 5; $i < count($foto); $i++ ){
                                                        $pics = $foto[$i];
                                                        switch ($i % 3){
                                                            case 1:
                                                                $classg = "der";
                                                                break;
                                                            case 2:
                                                                $classg = "izq";
                                                                break;
                                                            case 0:
                                                                $classg = "med";
                                                                break;
                                                            default:
                                                                $classg = "";
                                                                break;
                                                        }
                                                        switch ($j%2==0){
                                                            case 1:
                                                                $classp = "mizq";
                                                                break;
                                                            case 0:
                                                                $classp = "mder";
                                                                break;
                                                            default:
                                                                $classp = "";
                                                                break;
                                                        } $j++;
                                                        ?>
                                                        <li>
                                                            <a href="/amarillas1/businesscard/imagenes/<?=$pics?>"  class="<?=$classg?> <?=$classp?>" data-fancybox="gallery_foto">
                                                                <div class="ratio-box fade-box tamgal">
                                                                    <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="/amarillas1/businesscard/imagenes/<?=$pics?>" class="lazyload blur-up" title="<?=$nombre?> <?=$i?>" alt="<?=$nombre?> <?=$i?>">
                                                                </div>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        <?php } ?>

                  <?php } ?>

                </div>
                <div id="right_sidebar">
                    <?php if(isset($cmapa))  { ?>
                        <div class="box-cont">
                            <div class="hide" itemprop="geo" itemscope itemtype="https://schema.org/GeoCoordinates">
                                Latitude: <?=$clat?>
                                Longitude: <?=$clng?>
                                <meta itemprop="latitude" content="<?=$clat?>" />
                                <meta itemprop="longitude" content="<?=$clng?>" />
                            </div>
                            <h4 class="accordion">Mapa de Ubicación</h4>
                            <div class="panel">
                                <div id="map"></div>
                                <div class="clear"></div>
                                <div class="enl">
                                     <small><a href="https://www.openstreetmap.org/?mlat=<?=$clat?>&amp;mlon=<?=$clng?>#map=19/<?=$clat?>/<?=$clng?>" title="Ver mapa ampliado">Ver mapa más grande</a></small>

                                </div>
                                <div class="map_goo">
                                <?php if($navegador != "PC"){ ?>
                                    <a href="https://www.google.com/maps/dir//<?=$clat?>,<?=$clng?>" class="lleg"> <i class="fa fa-map-marked"></i> Cómo llegar</a>
                                <?php }else{?>
                                    <a href="https://www.google.com/maps/dir//<?=$clat?>,<?=$clng?>" class="lleg"> <i class="fa fa-map-marked"></i> Cómo llegar</a>
                                <?php }?>
                                </div>
                            </div>

                        </div>

                    <?php } ?>

                    <div id="inf_contacto">
                        <div class="box-cont"  itemscope itemtype="https://schema.org/Corporation">
                        <h3 class="accordion">Datos de Contacto</h3>
                        <div class="panel">
                            <div class="gi">
                                <?php if($direccion){?>
                                    <?php $nciudad = $nombreciudad == $nombredepto ? '<strong>'.mb_strtoupper($nombredepto, 'UTF-8').'</strong>' : $nombreciudad.', <strong>'.mb_strtoupper($nombredepto, 'UTF-8').'</strong>'; ?>
                                    <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress"><i class="fas fa-map-marker-alt"></i>
                                        <p class="rig_direc">
                                            <span itemprop="streetAddress" class="icon2" ><?php echo $direccion." - " ?></span>
                                            <span itemprop="addressLocality" class="icon2"><strong><?php echo $nciudad; ?></strong></span>
                                        </p>
                                    </div>
                                    <div class="clear"></div>
                                <?php } ?>
                                <?php if(isset($dias_horario)){ ?>
                                    <div class="hoy_horario">
                                        <div id="horario_reloj"><i class="<?=$tipo_icon?>"></i></div>
                                        <div class="div_table">
                                            <div class="div_row">
                                                <div class="div_cell bord_15">
                                                    <span class="font_hoy">Hoy: </span>
                                                </div>
                                                <?php if(count($arraydias[date("N")]) == 2){ // si esta cerrado el dia entero ?>
                                                    <div class="div_cell bord_90">
                                                        <span class="hora_cell">Cerrado</span>
                                                        <span class="estado_cerrado padestado">&#8226; Cerrado ahora</span>
                                                    </div>
                                                <?php }else{ ?>
                                                    <div class="div_cell bord_90" >
                                                        <?php for($j = 1; $j < count($arraydias[date("N")])-1; $j=$j+2){
                                                            if($arraydias[date("N")][$j] == 0 && $arraydias[date("N")][$j+1] == 48) { //Si trabaja 24horas?>
                                                                <div class="bord_100">
                                                                    <span class='hora_cell'>24 horas</span>
                                                                    <span class="estado_abierto padestado">&#8226; ABIERTO AHORA</span>
                                                                </div>
                                                                <?php break;
                                                            }else{ ?>
                                                                <div class="bord_100">
                                                                    <span class="hora_cell"><?=$opHora[$arraydias[date("N")][$j]]." - ".$opHora[$arraydias[date("N")][$j+1]] ?></span>
                                                                    <?php
                                                                    if($posi == 0 && $j == 1){?>
                                                                        <span class="<?=$class_estado?> padestado">&#8226; <?=$estado_horario;?></span>
                                                                    <?php }elseif($posi >= $j && $posi <= $j+1){ ?>
                                                                        <span class="<?=$class_estado?> padestado">&#8226; <?=$estado_horario;?></span>
                                                                        <?php
                                                                    }elseif($posi == count($arraydias[date("N")])-1){?>
                                                                        <span class="<?=$class_estado?> padestado">&#8226; <?=$estado_horario;?></span>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php }
                                                        } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                <?php }?>
                                <?php if($telefono1):?>
                                    <?php for($i = 0; $i < count($vtel); $i++):?>
                                    <div class="clear"></div>
                                        <p itemprop='telephone' class="icon1">
                                            <i class="fas fa-phone"></i>
                                            <?php if($navegador != "PC"){ ?>
                                                <a href="tel://<?= substr($codigotelf, -2,1).$vtel[$i] ?>"><?= "<span>Llamar ".$codigotelf."</span>  $vtel[$i]"; ?></a>
                                            <?php }else{ ?>
                                                <?= "<span>".$codigotelf."</span> $vtel[$i]"; ?>
                                            <?php } ?>
                                        </p>
                                    <?php endfor; ?>
                                <?php endif; ?>
                                <?php if($callfree):?>
                                    <p itemprop='telephone' class="icon1">
                                        <i class="fas fa-phone" style="font-size: 20px"></i>
                                        <?php if($navegador != "PC"){ ?>
                                            <a href="tel://<?= $callfree ?>"><span>Llamar</span> <?= $callfree; ?> </a>
                                        <?php }else{ ?>
                                            <?=$callfree; ?>
                                        <?php } ?>
                                    </p>
                                <?php endif; ?>
                                <?php if($celular):?>
                                    <?php for($i = 0; $i < count($vcel); $i++):?>
                                        <p itemprop='telephone' class="icon1">
                                            <i class="fas fa-mobile-alt"></i>
                                            <?php if($navegador != "PC"){ ?>
                                                <a href="tel://<?=$vcel[$i]?>"><?= "<span>Llamar (591)</span> ".$vcel[$i] ?></a>
                                            <?php }else{ ?>
                                                <?= "<span>(591)</span> ".$vcel[$i] ?>
                                            <?php } ?>
                                        </p>
                                    <?php endfor; ?>
                                <?php endif; ?>
                                <?php if($emergencia):?>
                                        <p itemprop='telephone' class="icon1">
                                            <i class="fas fa-mobile-alt"></i>
                                            <?php if($navegador != "PC"){ ?>
                                                <a href="tel://<?=$emergencia?>"><?= "<span><span style='color:#e74c3c';>Emergencias</span> (591)</span> ".$emergencia ?></a>
                                            <?php }else{ ?>
                                                <?= "<span>(591)</span> ".$emergencia." <span style='color:#e74c3c;margin-left: 5px;font-weight: bold'>Emergencias</span>" ?>
                                            <?php } ?>
                                        </p>
                                <?php endif; ?>
                                <?php if($fax){?>
                                    <p itemprop='faxNumber' class="icon fax"><i class="fas fa-fax"></i><?php echo "<span>".$codigotelf."</span> ".$fax ?></p>
                                <?php } ?>
                                <div>
                                    <?php
                                    if($wsapp!=""){
                                        if(!empty($whatsapp) and count($whatsapp)>0) {
                                            foreach ($whatsapp as $row){ ?>
                                                <p itemprop='telephone' class="icon1">
                                                    <i class="fab fa-whatsapp"></i>
                                                    <?php if($navegador=="PC"){?> <span>(591)</span> <?=$row?> <?php } else { ?> <a href="whatsapp://send?text=Hola&phone=+591<?=$row?>"><span>Chatear (591)</span> <?=$row?></a> <?php } ?>
                                                </p>
                                            <?php }
                                        }
                                    }else{
                                        if(isset($rsociales[6]) and $rsociales[6]!= "-") { ?>
                                            <p class="icon whats">
                                                <?php if($navegador=="PC"){ ?><i class="fab fa-whatsapp"></i> <span>(591)</span> <?=$rsociales[6]?> <?php } else { ?> <a href="whatsapp://send?text=Hola&phone=+591<?=$rsociales[6]?>"><i class="fab fa-whatsapp"></i><span>(591)</span> <?=$rsociales[6]?></a><?php }?>
                                            </p>
                                        <?php }
                                    }
                                    if(isset($rsociales[4]) and $rsociales[4] != "-") { ?>
                                        <p class="icon skype">
                                            <i class="fab fa-skype"></i>
                                            <a href="skype:<?=$rsociales[4]?>?call"><?=$rsociales[4]?></a>
                                        </p>
                                    <?php }
                                    /*if(isset($rsociales[7]) and $rsociales[7] != "-") { ?>
                                        <p style="background: url(../imagenes/sms.png) no-repeat 0 0; padding:5px 0 5px 40px; font-size:20px">
                                            <?php if($navegador=="PC") { echo $rsociales[7]; } else { ?> <a href="sms://<?=$rsociales[7]?>"><?=$rsociales[7]?></a><?php } ?>
                                        </p>
                                    <?php }*/ ?>
                                </div>
                                <?php  if(isset($web)){?>
                                    <span class="hide"><a href="https://<?=$web?>" itemprop='url'><?=$web?></a></span>
                                    <p class="ico_web">
                                        <i class="fas fa-globe-americas"></i>
                                        <a href="https://<?=$web?>" title="Web: https://<?=$web?>" target="_blank" class="ienlace"><?=$web?></a></p>
                                <?php } ?>

                                <?php if($pcontacto) {?>
                                    <p><?php echo $pcontacto;?></p>
                                <?php } ?>
								
                                <?php ///redes sociales
 								
								$conrs = 0;
								foreach($rsociales as $rsos){
									if($rsos != '-') $conrs++;										
								}								
								if($conrs > 0){
								
								?>
                                    <div class="social">
                                        <h3 class="social_text">Redes Sociales</h3>
                                        <ul>
                                            <?php
											if($rsociales[0] != "-") echo '<li><a href="'.$rsociales[0].'" target="_blank" class="i_0"><i class="fa fa-facebook"></i></a></li>';
											if($rsociales[1] != "-") echo '<li><a href="'.$rsociales[1].'" target="_blank" class="i_1"><i class="fa fa-x-twitter"></i></a></li>';
											if($rsociales[2] != "-") echo '<li><a href="'.$rsociales[2].'" target="_blank" class="i_2"><i class="fa fa-tiktok"></i></a></li>';
											if($rsociales[3] != "-") echo '<li><a href="'.$rsociales[3].'" target="_blank" class="i_3"><i class="fa fa-youtube"></i></a></li>';
											if($rsociales[4] != "-") echo '<li><a href="'.$rsociales[4].'" target="_blank" class="i_4"><i class="fa fa-skype"></i></a></li>';
											if($rsociales[6] != "-") {
												$rws = explode(",",$rsociales[6]);
												foreach($rws as $rw){
													echo '<li><a href="https://wa.me/+591'.$rw.'" target="_blank" class="i_6"><i class="fa fa-whatsapp"></i></a></li>';
												}														
											}											
											if($rsociales[7] != "-") echo '<li><a href="https://t.me/'.$rsociales[7].'" target="_blank" class="i_7"><i class="fa fa-telegram"></i></a></li>';
											if($rsociales[10] != "-") echo '<li><a href="'.$rsociales[10].'" target="_blank" class="i_10"><i class="fa fa-instagram"></i></a></li>';
											// if($rsociales[9] != "-") echo '<li><a href="'.$rsociales[9].'" target="_blank" class="i_9"><i class="fa fa-calendar-plus-o"></i></a></li>';
											if($rsociales[8] != "-") echo '<li><a href="'.$rsociales[8].'" target="_blank" class="i_8"><i class="fa fa-linkedin"></i></a></li>';
											
                                            
                                            /*$conso = 0;
                                            foreach($rsociales as $rsos){
                                                if($conso == 0) { if($rsos != "-") echo "<li><a href=\"$rsos\" target=\"_blank\" title=\"$nombre en Facebook\"><img src=\"/amarillas/imagenes/fc.png\" title='Facebook' alt='Facebook'></a></li>"; else echo "<li><img src=\"/amarillas/imagenes/nfc.png\" title='Facebook' alt='Facebook'></li>";}
                                                if($conso == 1) { if($rsos != "-") echo "<li><a href=\"$rsos\" target=\"_blank\" title=\"$nombre en Twitter\"><img src=\"/amarillas/imagenes/tw.png\" title='Twitter' alt='Twitter'></a></li>"; else echo "<li><img src=\"/amarillas/imagenes/ntw.png\" title='Twitter' alt='Twitter'></li>"; }
                                                if($conso == 3) { if($rsos != "-") echo "<li><a href=\"$rsos\" target=\"_blank\" title=\"$nombre en Youtube\"><img src=\"/amarillas/imagenes/yt.png\" title='Youtube' alt='Youtube'></a></li>"; else echo "<li><img src=\"/amarillas/imagenes/nyt.png\" title='Youtube' alt='Youtube'></li>";}
                                                if($conso == 5) { if($rsos != "-") echo "<li><a href=\"$rsos\" target=\"_blank\" title=\"$nombre en Tripadvisor\"><img src=\"/amarillas/imagenes/tr.png\" title='TripAdvisor' alt='TripAdvisor'></a></li>"; else echo "<li><img src=\"/amarillas/imagenes/ntr.png\" title='TripAdvisor' alt='TripAdvisor'></li>";}

                                                $conso++;
                                            }*/
                                            ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <div class="box-cont" id="ver_horario">
                        <?php if (isset($horarios)) { ?>
                            <h4 class="accordion">Horarios de Atención</h4>
                            <div class="panel">
                                <div class="dats" id="inf_horarios"><?= nl2br($horarios) ?></div>
                            </div>
                            <?php
                        } elseif(isset($dias_horario)) { ?>
                            <h4 class="accordion">Horarios de Atención</h4>
                            <div class="panel">
                                <div class="dats">
                                    <div class="div_table">
                                        <?php for($i = 1; $i < count($arraydias); $i++){ ?>
                                            <div class="div_row">
                                                <div class="div_cell bord_30">
                                                    <span class="dia_cell"><?=$OpDiaArray[$i]?>: </span>
                                                </div>
                                                <?php if(count($arraydias[$i]) == 2){ // si esta cerrado el dia entero ?>
                                                    <div class="div_cell bord_70">
                                                        <span class="hora_cell">Cerrado</span>
                                                        <?php if(date("N")== $i){ ?>
                                                            <span class="estado_cerrado">&#8226; Cerrado ahora</span>
                                                        <?php } ?>
                                                    </div>
                                                <?php }else{ ?>
                                                    <div class="div_cell bord_70">
                                                        <?php for($j = 1; $j < count($arraydias[$i])-1; $j=$j+2){
                                                            if($arraydias[$i][$j] == 0 && $arraydias[$i][$j+1] == 48) { //Si trabaja 24horas?>
                                                                <div class="bord_100">
                                                                    <span class='hora_cell'>24 horas</span>
                                                                    <?php if(date("N")== $i){?>
                                                                        <span class="estado_abierto">&#8226; Abierto ahora</span>
                                                                    <?php } ?>
                                                                </div>
                                                                <?php break;
                                                            }else{ ?>
                                                                <div class="bord_100">
                                                                    <span class="hora_cell"><?=$opHora[$arraydias[$i][$j]]." - ".$opHora[$arraydias[$i][$j+1]] ?></span>
                                                                    <?php if(date("N")== $i){
                                                                        if($posi == 0 && $j == 1){?>
                                                                            <span class="<?=$class_estado?>">&#8226; <?=ucfirst(strtolower($estado_horario));?></span>
                                                                        <?php }elseif($posi >= $j && $posi <= $j+1){ ?>
                                                                            <span class="<?=$class_estado?>">&#8226; <?=ucfirst(strtolower($estado_horario));?></span>
                                                                            <?php
                                                                        }elseif($posi == count($arraydias[$i])-1){?>
                                                                            <span class="<?=$class_estado?>">&#8226; <?=ucfirst(strtolower($estado_horario));?></span>
                                                                        <?php }
                                                                    } ?>
                                                                </div>
                                                            <?php }
                                                        } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    if(isset($checkin)){
                                        $ch = explode("|" , $checkin);
                                        ?>
                                        <p style="margin-top: 10px;">Check-In: <?=$opHora[$ch[0]]?></p>
                                        <p>Check-Out: <?=$opHora[$ch[1]]?></p>
                                    <?php } ?>

                                    <?php
                                    if(isset($mas_horario)){ ?>
                                        <p style="margin-top: 10px;"><?=$mas_horario?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if ($pagos) { ?>
                        <div class="box-cont">
                            <h4 class="accordion">Formas de Pago</h4>
                            <div class="panel">
                                <div class="dats" id="inf_pagos">
                                    <ul class="ico2">
                                        <?php $mpago = explode(",",$pagos);
                                        foreach ($mpago as $row){ ?>
                                            <li><span class="out_pagos"><?=ucfirst(trim($row))?></span></li>
                                        <?php } ?>
                                    </ul>
                                    <?php if ($tarjetas) { ?>
                                        <div class="tar">
                                            <?php
                                            foreach ($tarjeta as $cin) {
                                                echo "<img src=\"/amarillas/imagenes/$cin.png\" width=\"47\" height=\"29\" title=\"$TarjetasAltArray[$cin]\" alt=\"$TarjetasAltArray[$cin]\" > \n";
                                            } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    if(isset($sucursales)){
                        $sucur = $empresaDirectorio->buscarSucursal($sucursales);
                        $sucur1= $empresaDirectorio->buscarEmpresaBusiness($sucur[0]["codsucursal"]);
                        ?>
                        <div class="box-cont">
                            <h4 class="accordion">
                                <?php if($subguia == "139") echo "Sedes Académicas"; else echo "Sucursales"; ?>
                            </h4>
                            <div class="panel">
                                <ul class="suc">
                                    <?php $cSuc = $ciudadModel->getCiudad($sucur1[0]["codciudad"]);
                                    $dSuc = $deptoModel->getDescDepto($sucur1[0]["coddepto"]);
                                    $dSuc = $deptoModel->getDescDepto($sucur1[0]["coddepto"]);?>
                                    <li>
                                        <p style="padding:5px 0;font-size:16px;font-style: italic;">Casa Matriz</p>
                                        <strong><?=strtoupper($cSuc[0]["descripcion"])?>, </strong><?=$sucur1[0]["direccion"]?>
                                        <?php
                                        if($sucur1[0]["telefono"]){
                                            $tel = explode("|", $sucur1[0]["telefono"]);
                                            ?>
                                            <p><span><i class="fas fa-phone"></i> <?=$dSuc[0]["codtelefono"]." ". $tel[0]?></span> </p>
                                        <?php }elseif($sucur1[0]["celular"]){
                                            $tel = explode("|", $sucur1[0]["celular"]);?>
                                            <p><span><i class="fas fa-mobile-alt"></i> <?="(591) ". $tel[0]?></span> </p>
                                        <?php } ?>
                                        <a href="<?=$sucur1[0]["paginabusiness"]?>.html" title="Ver sucursal" target="_blank" class="btn_masd"><i class="fas fa-plus"></i> Más detalles</a>
                                    </li>
                                    <?php for($i=0; $i<count($sucur); $i++):?>
                                        <?php if($sucur[$i]["codempresa"] != $codigoempresa):?>
                                            <?php $cSuc = $ciudadModel->getCiudad($sucur[$i]["codciudad"]);
                                            $dSuc = $deptoModel->getDescDepto($sucur[$i]["coddepto"]);?>
                                            <li>
                                                <strong><?=mb_strtoupper($cSuc[0]["descripcion"])?>, </strong><?=$sucur[$i]["direccion"]?>
                                                <?php
                                                if($sucur[$i]["telefono"]){
                                                    $tel = explode("|", $sucur[$i]["telefono"]);
                                                    ?>
                                                    <p><span><i class="fas fa-phone"></i> <?=$dSuc[0]["codtelefono"]." ". $tel[0]?></span> </p>
                                                <?php }elseif($sucur[$i]["celular"]){
                                                    $tel = explode("|", $sucur[$i]["celular"]);?>
                                                    <p><span><i class="fas fa-mobile-alt"></i> <?="(591) ". $tel[0]?></span> </p>
                                                <?php } ?>
                                                <a href="<?=$sucur[$i]["paginabusiness"]?>.html" title="Ver sucursal" target="_blank" class="btn_masd"><i class="fas fa-plus"></i> Más detalles</a>
                                            </li>
                                        <?php endif;?>
                                    <?php endfor?>
                                </ul>
                            </div>
                        </div>
                    <?php }else{
                        $sucur = $empresaDirectorio->buscarSucursal($codigoempresa);
                        if(count($sucur)>0){?>
                            <div class="box-cont">
                                <h4 class="accordion">
                                    <?php if($subguia == "139") echo "Sedes Académicas"; else echo "Sucursales"; ?>
                                </h4>
                                <div class="panel">
                                    <ul class="suc">
                                        <?php for($i=0; $i<count($sucur); $i++):?>
                                            <?php if($sucur[$i]["codempresa"] != $codigoempresa):?>
                                                <?php $cSuc = $ciudadModel->getCiudad($sucur[$i]["codciudad"]);
                                                $dSuc = $deptoModel->getDescDepto($sucur[$i]["coddepto"]);?>
                                                <li>
                                                    <strong><?=mb_strtoupper($cSuc[0]["descripcion"])?>, </strong><?=$sucur[$i]["direccion"]?>
                                                    <?php
                                                    if($sucur[$i]["telefono"]){
                                                        $tel = explode("|", $sucur[$i]["telefono"]);
                                                        ?>
                                                        <p><span><i class="fas fa-phone"></i> <?=$dSuc[0]["codtelefono"]." ". $tel[0]?></span> </p>
                                                    <?php }elseif($sucur[$i]["celular"]){
                                                        $tel = explode("|", $sucur[$i]["celular"]);?>
                                                        <p><span><i class="fas fa-mobile-alt"></i> <?="(591) ". $tel[0]?></span> </p>
                                                    <?php } ?>
                                                    <a href="<?=$sucur[$i]["paginabusiness"]?>.html" title="Ver sucursal" target="_blank" class="btn_masd"><i class="fas fa-plus"></i> Más detalles</a>
                                                </li>
                                            <?php endif;?>
                                        <?php endfor?>
                                    </ul>
                                </div>
                            </div>
                        <?php }
                    }?>
                    <?php if($widgets) { echo '<div class="box-cont">'; echo $widgets; echo '</div>'; }?>

                        <?php if($personal){?>
                            <div class="box-cont">
                                <h4 class="accordion">Nuestro Equipo</h4>
                                <div class="panel">
                                    <div class="content_team">
                                        <ul>
                                            <?php
                                            $npersonal=explode(';',$personal);
                                         for ($i=0;$i<count($npersonal);$i++){
                                             $equipo = $empresaDirectorio->getEmpresaBusinessDatos($npersonal[$i]);
                                            foreach ($equipo as $row){
                                                ?>
                                                    <li>
                                                        <a href="https://www.boliviaentusmanos.com/amarillas/businesscard/<?=$row["paginabusiness"]?>.html">
                                                            <div class="cont_izq">
                                                                <div class="izq">
                                                                    <img src="https://www.boliviaentusmanos.com/amarillas/blogos/<?=$row["logo"]?>" alt="<?=ucwords(mb_strtolower($row["descripcion"]))?>">
                                                                </div>
                                                            </div>
                                                            <div class="cont_der">
                                                                <div class="der">
                                                                    <p><?=ucwords(mb_strtolower($row["descripcion"]))?></p>
                                                                    <span><?=$row["especialidad_profesional"]?></span>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <div class="clear"></div>
                                                    </li>
                                                    <?php } ?>
                                         <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>


                    <?php if($codigo_qr){?>
                        <div class="box-cont">
                            <h4 class="accordion">QR para compartir Tarjeta</h4>
                            <div class="panel">
                                <div id="content_qr">
                                    <div class="ratio-box fade-box tamqr">
                                        <img src="<?php echo generateQrCode($pageurl); ?>" data-src="<?php echo generateQrCode($pageurl); ?>" title="QR para compartir Tarjeta <?=$nombre?>" alt="Código QR <?=$nombre?>" class="img_qr lazyload blur-up">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <?php if($guia == "g007"){
        $empresa_similar = $empresaDirectorio->getEmpresasCiudad($ciudad,$codigoempresa, $nombre, 4);

        if(count($empresa_similar)>0){
            function twodshuffle($array)
            {
                // Get array length
                $count = count($array);
                // Create a range of indicies
                $indi = range(0, $count - 1);
                // Randomize indicies array
                shuffle($indi);
                // Initialize new array
                $newarray = array($count);
                // Holds current index
                $i = 0;
                // Shuffle multidimensional array
                foreach ($indi as $index) {
                    $newarray[$i] = $array[$index];
                    $i++;
                }
                return $newarray;
            }

            $empresa_similar=twodshuffle($empresa_similar);

        }

    }else{
        $codigoRubro = str_replace("-r", "-|r", $rubros);

        $empresa_similar = $empresaDirectorio->getEmpresasRelacionRubro($ciudad,$codigoempresa,$codigoRubro,$nombre,4);

        if(count($empresa_similar)>0){
            function twodshuffle($array)
            {
                // Get array length
                $count = count($array);
                // Create a range of indicies
                $indi = range(0, $count - 1);
                // Randomize indicies array
                shuffle($indi);
                // Initialize new array
                $newarray = array($count);
                // Holds current index
                $i = 0;
                // Shuffle multidimensional array
                foreach ($indi as $index) {
                    $newarray[$i] = $array[$index];
                    $i++;
                }
                return $newarray;
            }

            $empresa_similar=twodshuffle($empresa_similar);

        }




    }?>
    <div class="fcontent hrooms">
    </div>
    <!--Otras Empresas-->
    <?php if($tipo==1||$tipo==0){
        if(!empty($empresa_similar)) { ?>
            <div class="fcontent hrooms">
                <div class="rooms rooms1">
                    <?php if($guia == "g007"){ ?>
                        <h4><a href="/hoteles-bolivia/ciudad/<?=$ciudadseo?>/1/hoteles.html">Ver más Hoteles en <?=$nombreciudad?></a></h4>
                    <?php }else{ ?>
                        <h4>Otras empresas en <?=$nombreciudad?></h4>
                    <?php } ?>
                    <ul class="lista-rubros">
                        <?php
                        foreach($empresa_similar as $row){
                            $linkes = "/amarillas/businesscard/".$row["paginabusiness"].".html";
                            ?>
                            <li>
                                <h5 class="ver_mas"><a href="<?=$linkes?>" title="Business Card de: <?=str_ireplace(' *', '',$row['descripcion'])?>" target="_blank"><span> <?=str_ireplace(' *', '',$row['descripcion'])?></span></a></h5>
                            </li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if($tipo==2){
        if(!empty($empresa_similar)) { ?>
            <div class="fcontent hrooms">
                <div class="rooms-2 rooms1">
                    <?php if($guia == "g007"){ ?>
                        <h4><a href="/hoteles-bolivia/ciudad/<?=$ciudadseo?>/1/hoteles.html">Ver más Hoteles en <?=$nombreciudad?></a></h4>
                    <?php }else{ ?>
                        <h4>Otras empresas en <?=$nombreciudad?></h4>
                    <?php } ?>
                    <ul class="lista-rubros-2">
                        <?php
                        foreach($empresa_similar as $row){
                            $linkes = "/amarillas/businesscard/".$row['paginabusiness'].".html"; ?>
                            <li>
                                <div class="cont_img">
                                    <div class="img">
                                        <?php $fot=explode(';',$row['fotografias']); ?>
                                        <a href="<?=$linkes?>" title="<?=$row['descripcion']?>" target="_blank">
                                            <div class="ratio-box fade-box tamimg">
                                                <div class="ratio-box fade-box tamotras">
                                                     <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas1/businesscard/imagenes/<?=$fot[0]?>" class="lazyload blur-up" alt="<?=$row['descripcion']?>">
                                                </div>
                                        </a>
                                        <div class="descrip">
                                            <a href="<?=$linkes?>" target="_blank">
                                                <div class="text">
                                                    <h4 title="Business Card de: <?=str_ireplace(' *', '',$row['descripcion'])?>"><?=str_ireplace(' *', '',$row['descripcion'])?>
                                                    </h4></div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
        <?php if($tipo==3){ ?>
            <div class="fcontent hrooms">
                <div class="banner">
                <?php  if($cont_ausp==1){ for ($i=0;$i<$cont_ausp;$i++){ $aux=explode(';', $nauspiciadores);$aux2=$aux[$i];$ausp=$empresaDirectorio->getAuspiciadores($aux2);
                    foreach ($ausp as$row){?>
                        <h2>Enlaces Importantes</h2>
                        <div class="ban-1">
                            <a href="<?=$row['enlace']?>"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row['logo']?>" class="lazyload" title="<?=$row['descripcion']?>" alt="<?=$row['descripcion']?>"></a>
                        </div>
                    <?php }}} ?>
                <?php if($cont==2){?>
                    <div class="ban-2">
                        <h2>Enlaces Importantes</h2>
                        <ul>
                            <?php for ($i=0;$i<$cont_ausp;$i++){ $aux=explode(';', $nauspiciadores);$aux2=$aux[$i];$ausp=$empresaDirectorio->getAuspiciadores($aux2);foreach ($ausp as$row){ ?>
                                <li>
                                    <div class="cont_img">
                                        <a href="<?=$row['enlace']?>"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row['logo']?>" class="lazyload" title="<?=$row['descripcion']?>" alt="<?=$row['descripcion']?>"></a>
                                    </div>
                                </li>
                            <?php }}?>
                            <div class="clear"></div>
                        </ul>
                    </div>
                <?php }?>
                <?php if($cont==3){?>
                    <div class="ban-3">
                        <h2>Enlaces Importantes</h2>
                        <ul>
                            <?php for ($i=0;$i<$cont_ausp;$i++){ $aux=explode(';', $nauspiciadores);$aux2=$aux[$i];$ausp=$empresaDirectorio->getAuspiciadores($aux2);foreach ($ausp as$row){ ?>
                                <li>
                                    <div class="cont_img">
                                        <a href="<?=$row['enlace']?>"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row[logo]?>" class="lazyload" title="<?=$row['descripcion']?>" alt="<?=$row['descripcion']?>"></a>
                                    </div>
                                </li>
                            <?php }}?>
                            <div class="clear"></div>
                        </ul>
                    </div>
                <?php }?>
                <?php if($cont==4){?>
                    <div class="ban-4">
                        <h2>Enlaces Importantes</h2>
                        <ul>
                            <?php for ($i=0;$i<$cont_ausp;$i++){ $aux=explode(';', $nauspiciadores);$aux2=$aux[$i];$ausp=$empresaDirectorio->getAuspiciadores($aux2);foreach ($ausp as$row){ ?>
                                <li>
                                    <div class="cont_img">
                                        <a href="<?=$row['enlace']?>"> <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row[logo]?>" class="lazyload" title="<?=$row['descripcion']?>" alt="<?=$row['descripcion']?>"></a>
                                    </div>
                                </li>
                            <?php }}?>
                            <div class="clear"></div>
                        </ul>
                    </div>
                <?php }?>
            </div>
            </div>
            <?php if(!empty($empresa_similar)) { ?>
                <div class="fcontent hrooms">
                    <div class="rooms rooms1">
                        <?php if($guia == "g007"){ ?>
                            <h4><a href="/hoteles-bolivia/ciudad/<?=$ciudadseo?>/1/hoteles.html">Ver más Hoteles en <?=$nombreciudad?></a></h4>
                        <?php }else{ ?>
                            <h4>Otras empresas en <?=$nombreciudad?></h4>
                        <?php } ?>
                        <ul class="lista-rubros">
                            <?php
                            foreach($empresa_similar as $row){
                                $linkes = "/amarillas/businesscard/".$row["paginabusiness"].".html";
                                ?>
                                <li>
                                    <h6><a href="<?=$linkes?>" title="Business Card de: <?=str_ireplace(' *', '',$row['descripcion'])?>" target="_blank"><span><?=str_ireplace(' *', '',$row['descripcion'])?></span></a></h6>
                                </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
            <?php } ?>

        <?php } ?>
    <?php if($tipo==4){ ?>
        <div class="banner">
            <?php  if($cont==1){ for ($i=0;$i<$cont;$i++){ $aux=explode(';', $auspiciador);$aux2=$aux[$i];$ausp=$empresaDirectorio->getAuspiciadores($aux2);
                foreach ($ausp as$row){?>
                    <h2>Patrocinadores</h2>
                    <div class="ban-1">
                        <a href="<?=$row['enlace']?>"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row['logo']?>" class="lazyload" title="<?=$row['descripcion']?>" alt="<?=$row['descripcion']?>"></a>
                    </div>
                <?php }}} ?>
            <?php if($cont==2){?>
                <div class="ban-2">
                    <h2>Patrocinadores</h2>
                    <ul>
                        <?php for ($i=0;$i<$cont;$i++){ $aux=explode(';', $auspiciador);$aux2=$aux[$i];$ausp=$empresaDirectorio->getAuspiciadores($aux2);foreach ($ausp as$row){ ?>
                            <li>
                                <div class="cont_img">
                                    <a href="<?=$row['enlace']?>"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row['logo']?>" class="lazyload" title="Patrocinador" alt="Patrocinador"></a>
                                </div>
                            </li>
                        <?php }}?>
                        <div class="clear"></div>
                    </ul>
                </div>
            <?php }?>
            <?php if($cont==3){?>
                <div class="ban-3">
                    <h2>Patrocinadores</h2>
                    <ul>
                        <?php for ($i=0;$i<$cont;$i++){ $aux=explode(';', $auspiciador);$aux2=$aux[$i];$ausp=$empresaDirectorio->getAuspiciadores($aux2);foreach ($ausp as$row){ ?>
                            <li>
                                <div class="cont_img">
                                    <a href="<?=$row['enlace']?>"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row['logo']?>" class="lazyload" title="Auspiciador" alt="Auspiciador"></a>
                                </div>
                            </li>
                        <?php }}?>
                        <div class="clear"></div>
                    </ul>
                </div>
            <?php }?>
            <?php if($cont==4){?>
                <div class="ban-4">
                    <h2>Patrocinadores</h2>
                    <ul>
                        <?php for ($i=0;$i<$cont;$i++){ $aux=explode(';', $auspiciador);$aux2=$aux[$i];$ausp=$empresaDirectorio->getAuspiciadores($aux2);foreach ($ausp as$row){ ?>
                            <li>
                                <div class="cont_img">
                                    <a href="<?=$row['enlace']?>">
                                        <div class="ratio-box fade-box tamotras">
                                            <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row['logo']?>" class="lazyload" title="Auspiciador" alt="Auspiciador">
                                        </div>
                                    </a>
                                </div>
                            </li>
                        <?php }}?>
                        <div class="clear"></div>
                    </ul>
                </div>
            <?php }?>
        </div>
        <?php if(!empty($empresa_similar)) { ?>
            <div class="fcontent hrooms">
                <div class="rooms-2 rooms1">
                    <?php if($guia == "g007"){ ?>
                        <h4><a href="/hoteles-bolivia/ciudad/<?=$ciudadseo?>/1/hoteles.html">Ver más Hoteles en <?=$nombreciudad?></a></h4>
                    <?php }else{ ?>
                        <h4>Otras empresas en <?=$nombreciudad?></h4>
                    <?php } ?>
                    <ul class="lista-rubros-2">
                        <?php
                        foreach($empresa_similar as $row){
                            $linkes = "/amarillas/businesscard/".$row['paginabusiness'].".html";
                            ?>
                            <li>
                                <div class="cont_img">
                                    <div class="img">
                                        <?php $fot=explode(';',$row['fotografias']); ?>
                                        <a href="<?=$linkes?>" target="_blank">
                                            <div class="ratio-box fade-box tamotras">
                                                <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas1/businesscard/imagenes/<?=$fot[0]?>" class="lazyload blur-up" title="Amarillas" alt="Amarillas">
                                            </div>
                                        </a>
                                        <div class="descrip">
                                            <a href="<?=$linkes?>" target="_blank">
                                                <div class="text">
                                                    <h4 title="Business Card de: <?=str_ireplace(' *', '',$row['descripcion'])?>"><?=str_ireplace(' *', '',$row['descripcion'])?>
                                                    </h4></div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if($tipo==5){
//        echo "Tipo de Business Premium";
    } ?>
    <?php if($tipo==6){?>
        <div class="fcontent hrooms">
            <script src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" async></script><ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-8412187140631690" data-ad-slot="7585832437" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <script async>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
        <?php if(!empty($empresa_similar)) { ?>
            <div class="fcontent hrooms">
                <div class="rooms-2 rooms1">
                    <?php if($guia == "g007"){ ?>
                        <h4><a href="/hoteles-bolivia/ciudad/<?=$ciudadseo?>/1/hoteles.html">Ver más Hoteles en <?=$nombreciudad?></a></h4>
                    <?php }else{ ?>
                        <h4>Otras empresas en <?=$nombreciudad?></h4>
                    <?php } ?>
                    <ul class="lista-rubros-2">
                        <?php
                        foreach($empresa_similar as $row){
                            $linkes = "/amarillas/businesscard/".$row['paginabusiness'].".html"; ?>
                            <li>
                                <div class="cont_img">
                                    <div class="img">
                                        <?php $fot=explode(';',$row['fotografias']); ?>
                                        <a href="<?=$linkes?>" title="<?=$row['descripcion']?>" target="_blank">
                                            <div class="ratio-box fade-box tamotras">
                                                <img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas1/businesscard/imagenes/<?=$fot[0]?>" class="lazyload blur-up" alt="<?=$row['descripcion']?>">
                                            </div>
                                        </a>
                                        <div class="descrip">
                                            <a href="<?=$linkes?>" target="_blank">
                                                <div class="text">
                                                    <h4 title="Business Card de: <?=str_ireplace(' *', '',$row['descripcion'])?>"><?=str_ireplace(' *', '',$row['descripcion'])?>
                                                    </h4></div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <!--Business Card 7 -->
    <?php if($tipo==7){
        if($navegador=="MOBILE"){?>
            <div class="banner">
                <?php  $aux=explode(';', $auspiciador_cel);$aux2=$aux[1];$ausp=$empresaDirectorio->getAuspiciadores($aux2);
                $row=$ausp[0]?>
                <h2>Patrocinadores</h2>
                <div class="ban-1">
                    <a href="<?=$row['enlace']?>"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row['logo']?>" class="lazyload" title="<?=$row['descripcion']?>" alt="<?=$row['descripcion']?>"></a>
                </div>
            </div>
        <?php }else{ ?>
            <div id="wrapper">
                <div class="banner">
                    <?php  $aux=explode(';', $auspiciador);$aux2=$aux[1];$ausp=$empresaDirectorio->getAuspiciadores($aux2);
                    $row=$ausp[0]?>
                    <h2>Patrocinadores</h2>
                    <div class="ban-1">
                        <a href="<?=$row['enlace']?>"><img src="https://www.boliviaentusmanos.com/imagenes/betm.jpg" data-src="https://www.boliviaentusmanos.com/amarillas/businesscard/auspiciador/<?=$row['logo']?>" class="lazyload" title="<?=$row['descripcion']?>" alt="<?=$row['descripcion']?>"></a>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if(!empty($empresa_similar)) { ?>
            <div class="fcontent hrooms">
                <div class="rooms rooms1">
                    <?php if($guia == "g007"){ ?>
                        <h4><a href="/hoteles-bolivia/ciudad/<?=$ciudadseo?>/1/hoteles.html">Ver más Hoteles en <?=$nombreciudad?></a></h4>
                    <?php }else{ ?>
                        <h4>Otras empresas en <?=$nombreciudad?></h4>
                    <?php } ?>
                    <ul class="lista-rubros">
                        <?php
                        foreach($empresa_similar as $row){
                            $linkes = "/amarillas/businesscard/".$row['paginabusiness'].".html";
                            ?>
                            <li>
                                <h6><a href="<?=$linkes?>" title="Business Card de: <?=str_ireplace(' *', '',$row['descripcion'])?>" target="_blank"><span><?=str_ireplace(' *', '',$row['descripcion'])?></span></a></h6>
                            </li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <!--Fin Otras Empresas-->
    <?php if(isset($rubros) && $rubroasoc=="si"){?>
        <section class="fcontent hrooms">
            <div class="rooms rooms1" itemscope itemtype="https://schema.org/ItemList">
                <link itemprop="itemListOrder" href="https://schema.org/ItemListOrderDescending" />
                <ul class="lista-rubros">
                    <?php
                    for($i = 0 ; $i < count($rubross); $i++){
                        $webi1 = substr($rubross[$i],1);
                        $mybusca = $rubroModel->recuperarRubro($webi1);
                        if(count($mybusca)>0){
                            $am_rubro = substr($mybusca[0]['codrubro'], 1); ?>
                            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <a href="/amarillas/<?=$mybusca[0]['codrubro']?>/1/bolivia/<?=$mybusca[0]['burlseo']?>.html" title="Buscar <?=$mybusca[0]['descripcion']?>" target="_blank" itemprop="url"><div itemprop="name"><h5><?=$mybusca[0]['descripcion']?></h5></div></a>
                                <span class="hide" itemprop="position"><?=$i+1?></span>
                            </li>
                        <?php }
                    }?>
                </ul>
            </div>
        </section>
    <?php } ?>
</div>
<div class="fcontent color_blanco">
    <div id="nota">
        <div class="text"><h2><a href="/amarillas/search/?query=<?php echo $nombre1 ?>" title="<?=$nombre ?> en Páginas Amarillas"><?=$nombre ?> en Amarillas</a></h2></div>
        <div class="text1">Los datos desplegados son de referencia y sólo tienen el propósito de informar. Este documento no tiene ningún valor legal y no debe ser utilizado de manera distinta a su propósito. En caso de requerir mayores detalles y/o confirmar horarios de atención, productos, etc, el usuario deberá consultar directamente con la empresa.
            <br><a href="/" title="Boliviaentusmanos te recomienda">Es una recomendación de www.boliviaentusmanos.com</a>
        </div>
    </div>

</div>

<?php if($tipo==6){ ?>
    <div class="fcontent color_blanco">
        <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-8412187140631690" data-ad-slot="7585832437" data-ad-format="auto" data-full-width-responsive="true"></ins>
        <script async>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
<?php } ?>

<a class="scrollup">Scroll</a>
<div class="sidebar-offcanvas" id="sidebar"> <div class="list-group js-offcanvas">  <div class="wraper content"> <div class="menu-column"> <div class="menu-main"> <ul id="menu-main-menu" class="menu"> <li class="menu-item"><a href="/">PORTADA</a></li> <li class="menu-item"><a href="/amarillas/">AMARILLAS</a></li>  <li class="menu-item"><a href="/hoteles-bolivia/">HOTELES</a> </li> <li class="menu-item"><a href="/restaurantes-gastronomia/">RESTAURANTES</a>  </li> <li class="menu-item menu-item-has-children"><a href="/turismo/">TURISMO</a> <ul class="sub-menu"><li><a href="/turismo/atractivos-departamento/la-paz.html">Turismo en La Paz</a></li> <li><a href="/turismo/atractivos-departamento/cochabamba.html">Turismo en Cochabamba</a></li> <li><a href="/turismo/atractivos-departamento/santa-cruz.html">Turismo en Santa Cruz</a></li> <li><a href="/turismo/atractivos-departamento/chuquisaca.html">Turismo en Chuquisaca</a></li> <li><a href="/turismo/atractivos-departamento/potosi.html">Turismo en Potosí</a></li> <li><a href="/turismo/atractivos-departamento/oruro.html">Turismo en Oruro</a></li> <li><a href="/turismo/atractivos-departamento/tarija.html">Turismo en Tarija</a></li> </ul> </li><li class="menu-item"><a href="/guiamedica/">GUÍA MÉDICA</a></li> <li class="menu-item"><a href="/bolivia-industrias/">INDUSTRIAS</a></li><li class="menu-item"><a href="/tiendas-online-delivery/">TIENDAS ONLINE</a></li> </ul> </div> </div> </div> </div></div>
<?php if(isset($chat) && !empty($chat)){
    echo $chat;
} ?>
<?php include_once(ROOT1."include/footer3.inc.php"); ?>
<script>
    var map = L.map('map').setView([<?=$clat?>, <?=$clng?>], <?=$cmzm?>);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/#map=12/<?=$clat?>/<?=$clng?>" title="openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/" title="creativecommons.org">CC-BY-SA</a>, Imagery © <a href="https://cloudmade.com" title="cloudmade.com">CloudMade</a>',
        maxZoom: 19
    }).addTo(map);
    L.control.scale().addTo(map);
    L.marker([<?=$clat?>,<?=$clng?>],{draggable: true}).addTo(map);
</script>
<script src="/js/megamenu_actual/megamenu_plugins.js" ></script>
<script src="/js/megamenu_actual/megamenu.min.js" ></script>
<script src="/plugins/hiraku2/js/hiraku.min.js" ></script>
<script src="https://www.boliviaentusmanos.com/plugins/slick/slick.min.js" ></script>
<link rel="stylesheet" href="https://www.boliviaentusmanos.com/plugins/slick/slick-theme-min.css">
<?php if(isset($fotos) or isset($video) or isset($mail)){ ?>
    <script src="/plugins/fancybox/js/jquery.fancybox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/plugins/fancybox/css/jquery.fancybox.min.css" media="screen" />
<?php } ?>
<?php if($navegador!="MOBILE") { ?>
    <script src="/js/theia-sticky/dist/ResizeSensor.min.js"></script>
    <script src="/js/theia-sticky/dist/theia-sticky-sidebar.min.js"></script>
<?php }?>
<script src="/js/lazysizes.min.js"></script>
<script>
    if (window.location.href.indexOf('#_=_') > 0){window.location = window.location.href.replace('#_=_', '');}var html_element_id, html_element_2_id;$(document).ready(function() {new Hiraku(".js-offcanvas", {btn: ".js-offcanvas-btn", fixedHeader: ".js-fixed-header", direction: "left"});$("#buscador #search #query").focus();<?php if($navegador!="MOBILE") { ?>var leaderHeight = 130;$(window).scroll(function(){if ($(window).scrollTop() > leaderHeight){$("#nav").addClass("fixed-nav").css("top","0").next().css("padding-top","30px");} else {$("#nav").removeClass("fixed-nav").next().css("padding-top","0");}            });<?php } ?><?php if($navegador=="MOBILE") {if($tipo==8){ ?>var leaderHeightm = 0;$(window).scroll(function(){if ($(window).scrollTop() > leaderHeightm){$("#header").addClass("fixed-nav-header").css("top","0");$("#personal").css({"padding-top": "50px"});} else {$("#header").removeClass("fixed-nav-header");$("#personal").css({"padding-top": "0"});}});<?php } else{ ?>var leaderHeightm = 0;$(window).scroll(function(){if ($(window).scrollTop() > leaderHeightm){$("#header").addClass("fixed-nav-header").css("top","0");$("#search_content").css({"padding-top": "50px"});} else {$("#header").removeClass("fixed-nav-header");$("#search_content").css({"padding-top": "0"});}});<?php  } ?><?php  } ?>$(document).on("click", function (){$("#subnav").hide();});$( ".btn_config" ).on("click",function(event) {event.stopPropagation();$( "#subnav" ).slideToggle();});$("#subnav").on("click", function (event) {event.stopPropagation();});<?php if($navegador == "MOBILE"){ ?>$("#bquery").on("focus", function () {$( "#search_content #search select" ).show();$( "#search_content #search button" ).show();});<?php } ?>$("#form_buscar").submit(function(e) {if($("#bquery").val()===""){$("#bquery").focus();e.preventDefault();}});$("#form_search").submit(function (e) {if($("#mquery").val()===""){$("#mquery").focus();e.preventDefault();}});<?php if(isset($video)){ ?>$(".coments_in").fancybox({maxWidth:800, maxHeight:600, fitToView:false, width: '70%', height: '70%', autoSize: false, closeClick: false, openEffect: 'none', closeEffect: 'none'});<?php }?><?php if($navegador =="MOBILE") { ?>$(".menu-item-has-children").append("<div class='open-menu-link open'>+</div><div class='open-menu-link close'>-</div>");$('.open').addClass('visible');$('.open-menu-link').click(function(e){var childMenu=e.currentTarget.parentNode.children[1];if($(childMenu).hasClass('visible')){$(childMenu).removeClass("visible");$(e.currentTarget.parentNode.children[3]).removeClass("visible");$(e.currentTarget.parentNode.children[2]).addClass("visible");} else{$(childMenu).addClass("visible");$(e.currentTarget.parentNode.children[2]).removeClass("visible");$(e.currentTarget.parentNode.children[3]).addClass("visible");}});$(".mobile-nav").click(function(){$(".responsive-menu").addClass("expand");$(".menu-btn").addClass("btn-none");});$(".close-btn").click(function(){$(".responsive-menu").removeClass("expand");$(".menu-btn").removeClass("btn-none");});<?php } ?><?php if($navegador!="MOBILE") { ?>jQuery('#page, #right_sidebar').theiaStickySidebar({additionalMarginTop: 50});<?php }?>$(window).scroll(function(){if($(this).scrollTop() > 100) {$('.scrollup').fadeIn();}else {$('.scrollup').fadeOut();}});$('.scrollup').click(function(){$("html, body").animate({scrollTop: 0 }, 600);return false;});<?php if(isset($guia) and $guia == "g007" AND $habHotel){ ?>$.datetimepicker.setLocale('es');$(function(){$('#mindate').datetimepicker({format:'d/m/Y', minDate:'<?=date("d/m/Y")?>', onShow: function () {$('#mindate').blur();}, onSelectDate:function (ct,$i) {var nf = $("#mindate").val().split("/");var nfecha = nf[2]+"-"+nf[1]+"-"+nf[0];var fec = semana();sumaFecha = function(d, fecha){var Fecha = new Date();var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());var sep = sFecha.indexOf('/') != -1 ? '/' : '-';var aFecha = sFecha.split(sep);var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];fecha= new Date(fecha);fecha.setDate(fecha.getDate()+parseInt(d));var anno=fecha.getFullYear();var mes= fecha.getMonth()+1;var dia= fecha.getDate();mes = (mes < 10) ? ("0" + mes) : mes;dia = (dia < 10) ? ("0" + dia) : dia;var fechaFinal = dia+sep+mes+sep+anno;return (fechaFinal);};var fecha = sumaFecha(1,$("#mindate").val());var sfecha = fecha.split("/");$("#startDay").val(nfecha);$("#dia_in").html(diaSemana(nfecha));$("#dia_out").html(diaSemana(sfecha[2]+"-"+sfecha[1]+"-"+sfecha[0]));$('#maxdate').val(fecha).datetimepicker('show').datetimepicker('setOptions', {disabledDates: fec});}, timepicker:false});$('#maxdate').datetimepicker({format:'d/m/Y', onShow:function( ct ){this.setOptions({minDate:$('#mindate').val()?$('#mindate').val():false});$('#maxdate').blur();}, onSelectDate:function () {var inicio = $('#mindate').val().split("/");var fin = $('#maxdate').val().split("/");var finicio = new Date(Date.parse(inicio[2]+"/"+inicio[1]+"/"+inicio[0]));var ffin = new Date(Date.parse(fin[2]+"/"+fin[1]+"/"+fin[0]));var cont = ((ffin - finicio)/86400)/1000;$(".icon_noche").html(""+cont);$("#gdlr-check-out").val(fin[2]+"-"+fin[1]+"-"+fin[0]);$("#nrNights").val(cont);$("#dia_out").html(diaSemana(fin[2]+"-"+fin[1]+"-"+fin[0]));}, timepicker:false});});var dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado","Domingo"];function diaSemana(value){var date = new Date(value);var fechaString = value;var mes_name = parseInt( fechaString.substring(5,7))-1;var fechaNum = fechaString.substring(8,10);var year = fechaString.substring(0,4);return dias[date.getDay()];}function semana() {var hoy = '<?=date("d/m/Y")?>';var inicio = hoy.split("/");var fin = $('#mindate').val().split("/");var finicio = new Date(Date.parse(inicio[2]+"/"+inicio[1]+"/"+inicio[0]));var ffin = new Date(Date.parse(fin[2]+"/"+fin[1]+"/"+fin[0]));var cont = ((ffin - finicio)/86400)/1000;var cadena = [];for (var i = 0; i< cont+1; i++){var d = new Date();d.setDate(finicio.getDate()+i);cadena[i] = d.getFullYear()+"/"+("0"+(d.getMonth()+1)).slice(-2)+"/"+("0"+(d.getDate())).slice(-2);}return cadena;}<?php }?>init_contadorTa("rmensaje","contar_comentario", 250);var acc = document.getElementsByClassName("accordion");var i;for(i=0;i<acc.length;i++){acc[i].addEventListener("click", function() {this.classList.toggle("active");var panel = this.nextElementSibling;if (panel.style.maxHeight){panel.style.maxHeight = null;} else {panel.style.maxHeight = panel.scrollHeight + "px";}});}});function run_waitMeButton(boton,effect,msg){$("#"+boton).waitMe({effect: effect, text: msg, bg: 'rgba(255,255,255,0.7)', color: '#000', maxSize: '', source: '/plugins/waitme/img.svg', onClose: function(){}});}function init_contadorTa(idtextarea, idcontador,max){$("#"+idtextarea).keyup(function() {updateContadorTa(idtextarea, idcontador,max);});$("#"+idtextarea).change(function() {updateContadorTa(idtextarea, idcontador,max);});}function updateContadorTa(idtextarea, idcontador,max){var contador = $("#"+idcontador);var ta =     $("#"+idtextarea);contador.html("0/"+max);contador.html(ta.val().length+"/"+max);if(parseInt(ta.val().length)>max) {ta.val(ta.val().substring(0,max-1));contador.html(max+"/"+max);}}<?php if($navegador=="PC"){ ?> try{$(function () {var formData = "menu=mamarillas";var ruta = "/include/getMegamenu.php";$.ajax({url: ruta, type: "GET", data: formData, contentType: false, processData: false, async:true, success:function(html){$("#nav").html(html);$('.megamenu').megaMenuCompleteSet({menu_speed_show : 300, menu_speed_hide : 200, menu_speed_delay : 200, menu_effect : 'hover_fade', menu_click_outside : 0, menu_show_onload : 0, menu_responsive:1});}, error: function(html){console.log("Error: "+html);}});});}catch (e){console.log("Error: "+e);}<?php } ?>function mostrarSeleccion(nro){for(i=1;i<=14;i++){$("#content_"+i).removeClass("content_show").addClass("content_hide");$("#menu_"+i).removeClass("active");}$("#content_"+nro).removeClass("content_hide").addClass("content_show");$("#menu_"+nro).addClass("active");}function ratingStar(numStar, mediaId, starWidth, promRating, promCantidad){var nbrPixelsInDiv = numStar * starWidth;var numEnlightedPX = Math.round(nbrPixelsInDiv * promRating / numStar);var starBar = '<div id="'+mediaId+'" class="star_content">';starBar += '<div class="star_bar" style="display:inline-block;width:'+nbrPixelsInDiv+'px; height:'+starWidth+'px; background: linear-gradient(to right, #ffc600 0px,#ffc600 '+numEnlightedPX+'px,#ccc '+numEnlightedPX+'px,#ccc '+nbrPixelsInDiv+'px);" >';for (var i=1; i<=numStar; i++){starBar += '<div title="'+i+' de '+numStar+'" id="'+i+'" class="star"';starBar += '></div>';}starBar += '</div>';starBar += '<div class="resultMedia'+mediaId+'" style="display:inline-block;margin-left:10px;margin-top:5px;vertical-align:top;font-size: small; color: grey">';starBar += '</div>';starBar += '<div class="box'+mediaId+'"></div>';starBar += '</div>';return starBar;}function rateMedia(mediaId, rate, numStar, starWidth){$.fancybox.open({src: '#modal_rating', type : 'inline', opts : {afterShow : function( instance, current ) {$("#rating_user").html(ratingStar(5,501,25,rate,1));$("#ratinguser").val(rate);$('#form_rating')[0].reset();grecaptcha.reset();}}});}function overStar(mediaId, myRate, numStar){for( var i = 1; i <= numStar; i++ ) {if(i <= myRate) $('#' + mediaId + ' .star_bar #' + i).attr('class', 'star_hover'); else $('#' + mediaId + ' .star_bar #' + i).attr('class', 'star');}}function outStar(mediaId, myRate, numStar){for( var i = 1; i <= numStar; i++ ){$('#' + mediaId + ' .star_bar #' + i).attr('class', 'star');}}
</script>
<script>
    $('.your-class').slick({
        dots: true,
        infinite: true,
        autoplay: true,
        speed: 500,
        fade: true,
        nextArrow: '.slick_next',
        prevArrow: '.slick_previous',
        cssEase: 'linear'
    });
</script>
</body>
</html>