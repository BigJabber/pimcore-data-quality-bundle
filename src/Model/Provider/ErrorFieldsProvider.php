<?php
declare(strict_types=1);

namespace BigJabber\DataQualityBundle\Model\Provider;


use Basilicom\DataQualityBundle\DefinitionsCollection\Factory\FieldDefinitionFactory;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;
use Pimcore\Model\DataObject\DataQualityConfig;
use Symfony\Contracts\Translation\TranslatorInterface;

class ErrorFieldsProvider implements SelectOptionsProviderInterface
{
    private TranslatorInterface $translator;

    private FieldDefinitionFactory $fieldDefinitionFactory;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->fieldDefinitionFactory = new FieldDefinitionFactory();
    }

    public function getOptions($context, $fieldDefinition): array
    {
        $fields = [];
        if (!empty($fieldDefinition->optionsProviderData)) {
            $dataQualityId = $fieldDefinition->optionsProviderData;
            $dataQualityObject = DataObject::getById($dataQualityId);
            if (!empty($dataQualityObject)) {
                $dataQualityRules = $this->getDataQualityRules($dataQualityObject);
                
                foreach ($dataQualityRules as $dataQualityRuleGroupName => $dataQualityRuleGroup) {
                    $dataQualityFields = [];
        
                    /** @var FieldDefinition $fieldDefinition */
                    foreach ($dataQualityRuleGroup as $fieldDefinition) {
                        $fields[] = 
                        [
                            'key'   => $fieldDefinition->getTitle(),
                            'value' => $fieldDefinition->getFieldName(),
                        ];
                    }
                }
            }
        }
        return $fields;
    }


    public function getDefaultValue($context, $fieldDefinition): ?string
    {
        return $fieldDefinition->getDefaultValue();
    }

    public function hasStaticOptions($context, $fieldDefinition): bool
    {
        return false;
    }

    private function getDataQualityRules(DataQualityConfig $dataQualityConfig): array
    {
        $fieldCollection = $dataQualityConfig->getDataQualityRules();
        $items           = $fieldCollection->getItems();

        $rules = [];

        /** @var DataQualityFieldDefinition $item */
        foreach ($items as $item) {
            $group           = empty($item->getGroup()) ? FieldDefinitionFactory::DEFAULT_GROUP : $item->getGroup();
            $rules[$group][] = $this->fieldDefinitionFactory->get($item);
        }

        return $rules;
    }
}
