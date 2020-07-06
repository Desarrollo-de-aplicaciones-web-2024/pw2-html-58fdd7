<?php
require_once 'PruebasHTML.php';

class E03_Test extends PruebasHTML
{
    const DIR = 'E03' . DIRECTORY_SEPARATOR;
    const ARCHIVO = self::DIR . 'index.html';

    public function testSolucionCorrectaSistemas(){
        $archivo = self::ARCHIVO;
        $this->estructuraCorrectaDocumentoHTML( $this->root. $archivo );

        $str = str_ireplace(self::DOC_TYPE, '', file_get_contents( $this->root . $archivo));

        $doc = new DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML($str);

        $this->assertIsObject($doc, "No se pudo leer la estructura del documento ({$archivo}), revisa que sea un documento HTML válido");

        $inputs = $doc->getElementsByTagName('input');

        $this->assertEquals(9, count($inputs), 'Deben haber 9 elementos <input>');

        $input_nombre   = null;
        $input_email    = null;
        $input_password = null;
        $input_radios   = array();

        foreach ($inputs as $input){
            switch (trim($input->getAttribute('type'))){
                case 'text':
                    $input_nombre = $input;
                    break;
                case 'email':
                    $input_email = $input;
                    break;
                case 'password':
                    $input_password = $input;
                    break;
                case 'radio':
                    $input_radios[] = $input;
                    break;
            }
        }



        $this->assertNotNull($input_nombre, 'No se encontró el input de tipo texto');
        $this->assertNotEmpty(trim($input_nombre->getAttribute('name')), 'El campo para el nombre no tiene el atributo name o está vacío');
        $this->assertEquals('nombre', trim($input_nombre->getAttribute('name')), 'El atributo name del campo nombre no tiene el nombre correcto');

        $this->assertNotEmpty(trim($input_nombre->getAttribute('placeholder')), 'El campo para el nombre no tiene el atributo placeholder o está vacío');
        $this->assertEqualsIgnoringCase('nombre', trim($input_nombre->getAttribute('placeholder')), 'El atributo placeholder del campo nombre no tiene el nombre correcto (Nombre)');

        ///////////////////////////////////////////////////////

        $this->assertNotNull($input_email, 'No se encontró el input de tipo email');
        $this->assertNotEmpty(trim($input_email->getAttribute('name')),  'El campo para el e-mail no tiene el atributo name o está vacío');
        $this->assertEquals('email', trim($input_email->getAttribute('name')), 'El atributo name del campo email no tiene el nombre correcto');

        $this->assertNotEmpty(trim($input_email->getAttribute('placeholder')),  'El campo para el e-mail no tiene el atributo placeholder o está vacío');
        $this->assertEqualsIgnoringCase('e-mail', trim($input_email->getAttribute('placeholder')), 'El atributo placeholder del campo email no tiene el nombre correcto (E-mail)');

        ////////////////////////////////////////////////////////

        $this->assertNotNull($input_password, 'No se encontró el input de tipo password');
        $this->assertNotEmpty(trim($input_password->getAttribute('name')),  'El campo para la contraseña no tiene el atributo name o está vacío');
        $this->assertEquals('password', trim($input_password->getAttribute('name')), 'El atributo name del campo contraseña no tiene el nombre correcto');

        $this->assertNotEmpty(trim($input_password->getAttribute('placeholder')),  'El campo para la contraseña no tiene el atributo placeholder o está vacío');
        $this->assertEqualsIgnoringCase('contraseña', trim($input_password->getAttribute('placeholder')), 'El atributo placeholder del campo contraseña no tiene el nombre correcto (Contraseña)');

        ////////////////////////////////////////////////////////

        $this->assertEquals(2, count($input_radios), 'Deben haber 2 inputs de tipo radio');

        $validos = 0;
        $hombre_mujer = 0;
        foreach($input_radios as $radio){
            $name   = trim($radio->getAttribute('name'));
            $value  = trim($radio->getAttribute('value'));

            if($name == 'sexo'){
                $validos++;
            }

            if(($value == 'hombre' || $value == 'h') || ($value == 'mujer' || $value == 'm')){
                $hombre_mujer++;
            }
        }

        $this->assertEquals(2, $validos, 'Los 2 input de tipo radio deben tener el mismo valor en su atributo name (sexo)');
        $this->assertEquals(2, $hombre_mujer, 'El atributo value de alguno de los radios es incorrecto');


    }
}