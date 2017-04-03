<?

class CFormProfileReader
{

}

// require_once('utils_xml.php');

class CfprXml extends CFormProfileReader
{
    function readProfile($filename)
    {
        $parser = new CSimpleXmlParser('ISO-8859-1');
        $output = $parser->file2array(XML_PATH . '$filename', array('array', ''));
        return $output;
    }
}

class CfprIni extends CFormProfileReader
{
    function readProfile($filename)
    {
        if (file_exists($filename))
            $output = parse_ini_file($filename, true);
        else
        {
            echo 'File ' . $filename . ' not foud';
            $output = array();
        }
        return $output;
    }
}

?>