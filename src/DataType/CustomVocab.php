<?php
namespace CustomVocab\DataType;

use CustomVocab\Api\Representation\CustomVocabRepresentation;
use Omeka\Api\Adapter\AbstractEntityAdapter;
use Omeka\DataType\Literal;
use Omeka\Entity\Value;
use Zend\Form\Element\Select;
use Zend\View\Renderer\PhpRenderer;

class CustomVocab extends Literal
{
    /**
     * @var CustomVocabRepresentation
     */
    protected $vocab;

    /**
     * Constructor
     *
     * @param CustomVocabRepresentation $vocab
     */
    public function __construct(CustomVocabRepresentation $vocab)
    {
        $this->vocab = $vocab;
    }

    public function getLabel()
    {
        return sprintf('Custom Vocab: “%s”', $this->vocab->label());
    }

    public function getTemplate(PhpRenderer $view)
    {
        // Normalize vocab terms for use in a select element.
        $terms = array_map('trim', explode(PHP_EOL, $this->vocab->terms()));
        $valueOptions = array_combine($terms, $terms);

        $select = new Select('customvocab');
        $select->setAttributes([
                'class' => 'terms',
                'data-value-key' => '@value',
            ])
            ->setEmptyOption('Select Below')
            ->setValueOptions($valueOptions);
        return $view->formSelect($select);
    }

    public function hydrate(array $valueObject, Value $value, AbstractEntityAdapter $adapter)
    {
        $valueObject['@language'] = $this->vocab->lang();
        parent::hydrate($valueObject, $value, $adapter);
    }
}
