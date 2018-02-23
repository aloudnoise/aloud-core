<?php

/**
 * @copyright Copyright &copy; Thiago Talma, thiagomt.com, 2014
 * @package yii2-jstree
 * @version 1.0.0
 */

namespace app\widgets\EJsTree;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;


/**
 * JsTree widget is a Yii2 wrapper for the jsTree jQuery plugin.
 *
 * @author Thiago Talma <thiago@thiagomt.com>
 * @since 1.0
 * @see http://jstree.com
 */
class JsTree extends InputWidget
{
    /**
     * @var array Data configuration.
     * If left as false the HTML inside the jstree container element is used to populate the tree (that should be an unordered list with list items).
     */
    public $data = [];

    /**
     * @var array Stores all defaults for the core
     */
    public $core = [
        'expand_selected_onload' => true,
        'themes' => [
            'icons' => false
        ]
    ];

    /**
     * @var array Stores all defaults for the checkbox plugin
     */
    public $checkbox = [
        'three_state' => true,
        'keep_selected_style' => false];


    /**
     * @var array Stores all defaults for the drag'n'drop plugin
     */
    public $dnd = [];

    /**
     * @var array Stores all defaults for the search plugin
     */
    public $search = [];

    /**
     * @var string the settings function used to sort the nodes.
     * It is executed in the tree's context, accepts two nodes as arguments and should return `1` or `-1`.
     */
    public $sort = [];

    public $rules = [];

    /**
     * @var array Stores all defaults for the state plugin
     */
    public $state = [];

    /**
     * @var array Configure which plugins will be active on an instance. Should be an array of strings, where each element is a plugin name.
     */
    public $plugins = ["checkbox"];


    /**
     * @var array Stores all defaults for the types plugin
     */
    public $types = [
        '#' => [],
        'default' => [],
    ];


    public $ui;

    public $contextmenu_function;

    public $action_js;

    public $persistence;

    /**
     * @inheritdoc
     */
    public function init()
    {


        parent::init();
        $this->registerAssets();

        if (!$this->hasModel()) {
            echo Html::hiddenInput($this->options['id'], null, [ 'id' => $this->options['id'] ]);
        }
        else {

                   echo Html::activeTextInput($this->model, $this->attribute, ['class' => 'hidden', 'value' => $this->value]);
            Html::addCssClass($this->options, "js_tree_{$this->attribute}");
        }

        $this->options['id'] = 'jsTree_' . $this->options['id'];
        
        echo Html::tag('div', '', $this->options);

        ?>

<?PHP
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();

        $config = [
            'core' => array_merge(['data' => $this->data], $this->core),
            'checkbox' => $this->checkbox,
            'rules' => $this->rules,
            'dnd' => $this->dnd,
            'search' => $this->search,
            'sort' => $this->sort,
            'state' => $this->state,
            'plugins' => $this->plugins,
            'types' => $this->types,
            'ui' => $this->ui,

        ];
        $defaults =  '{'.trim(Json::encode($config), "{}").','.'"contextmenu":{"items": contextmenu}'.'}';

        $inputId = (!$this->hasModel()) ? $this->options['id'] : Html::getInputId($this->model, $this->attribute);


        $js = <<<SCRIPT
;(function($, window, document, undefined) {
    var treeview = $('#jsTree_{$this->options['id']}')
        .bind("loaded.jstree", function (event, data) {
                $("#{$inputId}").val(JSON.stringify(data.selected));
            })
        .bind("changed.jstree", function(e, data, x){
                $("#{$inputId}").val(JSON.stringify(data.selected));
        });

        function expandNode(value, tree){
    // Expand all nodes up to the root (the id of the root returns as '#')
    var nodeID = value;
    while (nodeID != '#') {
        // Open this node

        if (nodeID != value) {
        tree.jstree("open_node", nodeID);
        }
        // Get the jstree object for this node
        var thisNode = tree.jstree("get_node", nodeID);
        // Get the id of the parent of this node
        nodeID = tree.jstree("get_parent", thisNode);

    }
    $(value+'_anchor').click();
}
        treeview.jstree({$defaults}){$this->action_js};

        function contextmenu(node) {
            {$this->contextmenu_function}
        }

})(window.jQuery, window, document);
SCRIPT;
        $view->registerJs($js);


    }
}
