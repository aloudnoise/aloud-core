<?php

/* recovery.twig */
class __TwigTemplate_36674b34240789d2364026779168ba4419b9ba766f50cfe778fbf7a872fcf0a7 extends Twig_Template
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
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('void')->getCallable(), array(yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "setTitle", array(0 => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Восстановление пароля"))), "method"))), "html", null, true);
        echo "
<div class=\"action-content\">
    <div>
        ";
        // line 4
        $this->env->getExtension('yii\twig\Extension')->addUses("app/widgets/EForm/EForm");
        echo "
        ";
        // line 5
        $context["form"] = $this->env->getExtension('yii\twig\Extension')->beginWidget("e_form", array("htmlOptions" => array("id" => "recoveryForm", "method" => "post", "action" => $this->env->getExtension('yii\twig\Extension')->path(array(0 => "/auth/recovery")))));
        // line 12
        echo "
        <div class=\"form-group\" attribute=\"fk\">
            <div class=\"input-group\">
                <input type=\"hidden\" type=\"text\" class=\"form-control\" name=\"fk\" value=\"";
        // line 15
        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["model"] ?? null), "fk", array()), "html", null, true);
        echo "\" />
            </div>
        </div>

        <div class=\"form-group\" attribute=\"password\">
            <label for=\"exampleInputEmail1\">";
        // line 20
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Новый пароль")), "html", null, true);
        echo "</label>
            <div class=\"input-group\">
                <span class=\"input-group-addon text-light-gray\" id=\"basic-addon1\"><i class=\"fa fa-certificate\"></i></span>
                <input autocomplete=\"off\" type=\"password\" class=\"form-control\" name=\"password\" />
            </div>
            <small id=\"emailHelp\" class=\"form-text text-muted\">";
        // line 25
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "")), "html", null, true);
        echo "</small>
        </div>

        <div class=\"form-group\" attribute=\"password_duplicate\">
            <label for=\"exampleInputEmail1\">";
        // line 29
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Повторите пароль")), "html", null, true);
        echo "</label>
            <div class=\"input-group\">
                <span class=\"input-group-addon text-light-gray\" id=\"basic-addon2\"><i class=\"fa fa-certificate\"></i></span>
                <input autocomplete=\"off\" type=\"password\" class=\"form-control\" name=\"password_duplicate\" />
            </div>
            <small id=\"emailHelp\" class=\"form-text text-muted\">";
        // line 34
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "")), "html", null, true);
        echo "</small>
        </div>

        <div class=\"form-group\">
            <input style=\"width:100%;\" type=\"submit\" class=\"text-center btn btn-success\" value=\"";
        // line 38
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Сменить пароль")), "html", null, true);
        echo "\" />
        </div>
        ";
        // line 40
        $this->env->getExtension('yii\twig\Extension')->endWidget("e_form");
        echo "
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "recovery.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  79 => 40,  74 => 38,  67 => 34,  59 => 29,  52 => 25,  44 => 20,  36 => 15,  31 => 12,  29 => 5,  25 => 4,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{{ void(this.setTitle(translate('main','Восстановление пароля'))) }}
<div class=\"action-content\">
    <div>
        {{ use('app/widgets/EForm/EForm') }}
        {% set form = e_form_begin({
            'htmlOptions' : {
            'id' : 'recoveryForm',
            'method' : 'post',
            'action' : path(['/auth/recovery'])
        }
        }) %}

        <div class=\"form-group\" attribute=\"fk\">
            <div class=\"input-group\">
                <input type=\"hidden\" type=\"text\" class=\"form-control\" name=\"fk\" value=\"{{ model.fk }}\" />
            </div>
        </div>

        <div class=\"form-group\" attribute=\"password\">
            <label for=\"exampleInputEmail1\">{{ translate('main', 'Новый пароль') }}</label>
            <div class=\"input-group\">
                <span class=\"input-group-addon text-light-gray\" id=\"basic-addon1\"><i class=\"fa fa-certificate\"></i></span>
                <input autocomplete=\"off\" type=\"password\" class=\"form-control\" name=\"password\" />
            </div>
            <small id=\"emailHelp\" class=\"form-text text-muted\">{{ translate('main', '') }}</small>
        </div>

        <div class=\"form-group\" attribute=\"password_duplicate\">
            <label for=\"exampleInputEmail1\">{{ translate('main', 'Повторите пароль') }}</label>
            <div class=\"input-group\">
                <span class=\"input-group-addon text-light-gray\" id=\"basic-addon2\"><i class=\"fa fa-certificate\"></i></span>
                <input autocomplete=\"off\" type=\"password\" class=\"form-control\" name=\"password_duplicate\" />
            </div>
            <small id=\"emailHelp\" class=\"form-text text-muted\">{{ translate('main', '') }}</small>
        </div>

        <div class=\"form-group\">
            <input style=\"width:100%;\" type=\"submit\" class=\"text-center btn btn-success\" value=\"{{ translate('main','Сменить пароль') }}\" />
        </div>
        {{ e_form_end() }}
    </div>
</div>", "recovery.twig", "E:\\dists\\openServer\\OpenServer\\domains\\krw\\protected\\web\\views\\auth\\recovery.twig");
    }
}
