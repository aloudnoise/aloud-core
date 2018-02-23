<?php

/* form.twig */
class __TwigTemplate_d683e46f219e86730df3ffdbaa63c704632bd43caa3cf7aa41a7e39568543390 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('void')->getCallable(), array(yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "setTitle", array(0 => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Добавить запись"))), "method"))), "html", null, true);
        echo "
<div class=\"action-content\">
    
    ";
        // line 4
        $this->env->getExtension('yii\twig\Extension')->addUses("app/widgets/EForm/EForm");
        echo "
    ";
        // line 5
        $context["form"] = $this->env->getExtension('yii\twig\Extension')->beginWidget("e_form", array("htmlOptions" => array("id" => "exampleForm", "method" => "post", "action" => call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/example/add", "id" => yii\twig\Template::attribute($this->env, $this->getSourceContext(),         // line 9
($context["model"] ?? null), "id", array())))))));
        // line 12
        echo "    
    <!-- attribute='x' Обязательный аттрибут для формы на бекбоне -->
    <div class=\"form-group\" attribute=\"name\" >
        <label class=\"control-label\">Name</label>
        <input type=\"text\" name=\"name\" class=\"form-control\" value=\"";
        // line 16
        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["model"] ?? null), "name", array()), "html", null, true);
        echo "\" placeholder=\"Name\" />
    </div>

    <div class=\"form-group\" attribute=\"one_attr\" >
        <label class=\"control-label\">one</label>
        <input type=\"text\" name=\"one\" class=\"form-control\" value=\"";
        // line 21
        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["model"] ?? null), "one_attr", array()), "html", null, true);
        echo "\" placeholder=\"one_attr\" />
    </div>

    <div class=\"form-group\" attribute=\"two_attr\" >
        <label class=\"control-label\">two</label>
        <input type=\"text\" name=\"two\" class=\"form-control\" value=\"";
        // line 26
        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["model"] ?? null), "two_attr", array()), "html", null, true);
        echo "\" placeholder=\"two_attr\" />
    </div>

    <div class=\"form-group\" attribute=\"three_attr\" >
        <label class=\"control-label\">three</label>
        <input type=\"text\" name=\"three\" class=\"form-control\" value=\"";
        // line 31
        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["model"] ?? null), "three_attr", array()), "html", null, true);
        echo "\" placeholder=\"three_attr\" />
    </div>
    
    <div class=\"form-group\">
        <button class=\"btn btn-primary\">";
        // line 35
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Сохранить")), "html", null, true);
        echo "</button>
    </div>
    
</div>";
    }

    public function getTemplateName()
    {
        return "form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  69 => 35,  62 => 31,  54 => 26,  46 => 21,  38 => 16,  32 => 12,  30 => 9,  29 => 5,  25 => 4,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{{ void(this.setTitle(translate('main','Добавить запись'))) }}
<div class=\"action-content\">
    
    {{ use('app/widgets/EForm/EForm') }}
    {% set form = e_form_begin({
    'htmlOptions' : {
    'id' : 'exampleForm',
    'method' : 'post',
    'action' : organizationPath({0: '/example/add', id: model.id})
    }
    }) %}
    
    <!-- attribute='x' Обязательный аттрибут для формы на бекбоне -->
    <div class=\"form-group\" attribute=\"name\" >
        <label class=\"control-label\">Name</label>
        <input type=\"text\" name=\"name\" class=\"form-control\" value=\"{{ model.name }}\" placeholder=\"Name\" />
    </div>

    <div class=\"form-group\" attribute=\"one_attr\" >
        <label class=\"control-label\">one</label>
        <input type=\"text\" name=\"one\" class=\"form-control\" value=\"{{ model.one_attr }}\" placeholder=\"one_attr\" />
    </div>

    <div class=\"form-group\" attribute=\"two_attr\" >
        <label class=\"control-label\">two</label>
        <input type=\"text\" name=\"two\" class=\"form-control\" value=\"{{ model.two_attr }}\" placeholder=\"two_attr\" />
    </div>

    <div class=\"form-group\" attribute=\"three_attr\" >
        <label class=\"control-label\">three</label>
        <input type=\"text\" name=\"three\" class=\"form-control\" value=\"{{ model.three_attr }}\" placeholder=\"three_attr\" />
    </div>
    
    <div class=\"form-group\">
        <button class=\"btn btn-primary\">{{ translate('main', 'Сохранить') }}</button>
    </div>
    
</div>", "form.twig", "E:\\dists\\openServer\\OpenServer\\domains\\krw\\protected\\web\\views\\example\\form.twig");
    }
}
