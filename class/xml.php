<?php
require_once("err.php");
class xml{
    private $documentxml;
    private $date;
    private array $arrSeries;
    private string $guid;
    private int $TotalLineItems;

    public function __construct(array $series)
    {
        $this->date = date('Y-m-d');
        $this->guid = $this->guidv4();
        $this->arrSeries = $series;
        $this->TotalLineItems = $this->getTotalLineItems();
        libxml_clear_errors();
    }

    public function crearXML() {
        $this->documentxml = new DOMDocument('1.0', 'utf-8');
        $this->documentxml->formatOutput = true;
        $PbrExportContent   = $this->documentxml->createElement("PbrExportContent");
          $PbrExportContent->setAttribute("xmlns:i","http://www.w3.org/2001/XMLSchema-instance");
          $PbrExportContent->setAttribute("xmlns","http://schemas.ms.it.oem/digitaldistribution/2010/10");
          $PbrExportContent     = $this->documentxml->appendChild($PbrExportContent);
            $CloudOASiteId      = $this->documentxml->createElement("CloudOASiteId","573");
            $CloudOASiteId      = $PbrExportContent->appendChild($CloudOASiteId);
            $CloudOABusinessId  = $this->documentxml->createElement("CloudOABusinessId","605");
            $CloudOABusinessId  = $PbrExportContent->appendChild($CloudOABusinessId);
            $CloudOAUserId      = $this->documentxml->createElement("CloudOAUserId","3311");
            $CloudOAUserId      = $PbrExportContent->appendChild($CloudOAUserId);
            $CreatedDateUTC     = $this->documentxml->createElement("CreatedDateUTC",$this->date);
            $CreatedDateUTC     = $PbrExportContent->appendChild($CreatedDateUTC);
            $Content            = $this->documentxml->createElement("Content");
              $Content->setAttribute("i:type","ProductBindingReportRequest");
              $Content            = $PbrExportContent->appendChild($Content);
              $CustomerBindingUniqueID  = $this->documentxml->createElement("CustomerBindingUniqueID",$this->guid);
              $CustomerBindingUniqueID  = $Content->appendChild($CustomerBindingUniqueID);
              $SoldToCustomerID         = $this->documentxml->createElement("SoldToCustomerID","R231");
              $SoldToCustomerID         = $Content->appendChild($SoldToCustomerID);
              $ReceivedFromCustomerID   = $this->documentxml->createElement("ReceivedFromCustomerID","R231");
              $ReceivedFromCustomerID   = $Content->appendChild($ReceivedFromCustomerID);
              $TotalLineItems           = $this->documentxml->createElement("TotalLineItems",$this->TotalLineItems);
              $TotalLineItems           = $Content->appendChild($TotalLineItems);
              $ProductBindings          = $this->documentxml->createElement("ProductBindings");
              $ProductBindings          = $Content->appendChild($ProductBindings);
                foreach( $this->arrSeries as $row){
                  $ProductBinding         = $this->documentxml->createElement("ProductBinding");
                  $ProductBinding         = $ProductBindings->appendChild($ProductBinding);
                    $WindowsProductKeyID  = $this->documentxml->createElement("WindowsProductKeyID", trim($row[0]));
                    $WindowsProductKeyID  = $ProductBinding->appendChild($WindowsProductKeyID);
                    $ServiceProductKeyID  = $this->documentxml->createElement("ServiceProductKeyID", trim($row[1]));
                    $ServiceProductKeyID  =$ProductBinding->appendChild($ServiceProductKeyID);
                }
        return $this->documentxml;
    }

    public function getTotalLineItems():int
    {
        $this->TotalLineItems = count($this->arrSeries);
        return $this->TotalLineItems;
    }

    public function guidv4()
    {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return trim(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)));
    }
}

?>