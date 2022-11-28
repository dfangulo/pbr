<?php
require_once "class/xml.php";
    if (isset($_POST['btn_crear_xml'])) {
        if (!empty($_POST['input_datos_series'])) {
            if (!empty(callArray($_POST['input_datos_series']))) {
                $newArray = callArray($_POST['input_datos_series']);
                $xml = new xml($newArray);
                $last_textArea_input = trim($_POST['input_datos_series']);
            }
        }
    }

    function callArray($arrArray)
    {
        $arrArray = trim($arrArray);
        $array = explode("\r", $arrArray);
        foreach ($array as $row) {
            $arrSeries[] = explode(",", $row);
        }
        return $arrSeries;
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>XML PBR</title>
        <link rel="stylesheet" href="../bootstrap-5.0.2-dist/css/bootstrap.min.css">
    </head>
    <body>
        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="#">
                <img src="img/xml.png" width="30" height="30" class="d-inline-block align-top" alt="">
                XML - builder - PBR
            </a>
        </nav>
        <div class="container">
            <form action="" method="post" class="container">
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label for="text-area-input_datos_series" class="form-label form-control-lg">INTRODUCIR LLAVES</label>
                        <textarea name="input_datos_series" id="text-area-input_datos_series" class="form-control col-md-12" rows="23" placeholder="WindowsID, OfficeID"><?php if (!empty($last_textArea_input)) { echo trim($last_textArea_input);};?></textarea>
                        <div class="row mt-3">
                            <div class="col-auto">
                                <button type="submit" class="btn btn-dark col-auto" name="btn_crear_xml">CREAR XML</button>
                            </div>
                        </div>
                        <!-- <input type="file" name="archivo" id="archivoInputcsv" value="Archivo csv"> -->
                    </div>
                    <div class="col-md-9">
                        <label for="textareaXml" class="form-label form-control-lg" placeholder="XML preview">VISTA PREVIA XML</label>
                        <textarea id="textareaXml" class="form-control col-md-12" rows="23" aria-label="vista preliminar..." disabled readonly><?php if (!empty($_POST['input_datos_series'])) {$array = callArray($_POST['input_datos_series']);$xmlview = new xml($array);$strXML = $xmlview->crearXML();print $strXML->saveXML();}?></textarea>
                        <div class="row mt-3">
                            <?php
                                if (!empty($_POST['input_datos_series'])) {
                                    echo '<div class="col-auto"><input type="text" class="form-control"  id="nombre-archivo" placeholder = "Nombre Archivo">         </div><div class="col-auto"><button name="btn_crear_xml" class="btn btn-dark col-auto" id="guardar">Descargar</button></div>';
                                } else {
                                    echo '<div class="col-auto"><input type="text" class="form-control"  id="nombre-archivo" placeholder = "Nombre Archivo" readonly></div><div class="col-auto"><button class="btn btn-dark col-auto"     disabled>Descargar</button></div>';
                                };
                            ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <script>
            // escuchar si en la entrada del nombre de archivo, se da enter
            var inputFileName = document.getElementById("nombre-archivo");
            inputFileName.addEventListener("keypress",function(event) {
                    if (event.key === "Enter"){
                        event.preventDefault();
                        document.getElementById("guardar").click();
                    }
            });
            // escuchar si de le da clic al boton descargar
            document.querySelector('#guardar').onclick = function () {
                const data = document.querySelector('#textareaXml').value;
                const nombreArchivo = document.querySelector("#nombre-archivo").value;
                exportar(data, nombreArchivo);
            }
            //funcion para exportar/descargar un archivo con o sin nombre en formato xml (descargas)
            function exportar (data, filename){
                const a = document.createElement("a");
                let contenido = data,
                    blob = new Blob([contenido],{type: "text/xml"});
                    url = URL.createObjectURL(blob);
                a.href = url;
                a.download = filename;
                a.click();
                URL.revokeObjectURL(url);
            }
        </script>
    </body>
</html>