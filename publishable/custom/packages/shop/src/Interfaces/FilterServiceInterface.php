<?php

namespace EvolutionCMS\Shop\Interfaces;

interface FilterServiceInterface
{
    public function renderForm();
    public function getFilteredCatalog(int $parentId, array $options = []);
}