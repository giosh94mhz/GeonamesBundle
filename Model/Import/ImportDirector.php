<?php
namespace Giosh94mhz\GeonamesBundle\Model\Import;

interface ImportDirector
{
    public function addStep(ImportStepBuilder $builder);

    public function getDispatcher();

    public function import();

    public function download();
}
