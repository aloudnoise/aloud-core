<?php

/* restore.twig */
class __TwigTemplate_06dff326e5c2b723e561fccaf79b3934ac16a1a8288ae563ee8d9d6e15712c16 extends Twig_Template
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
    ";
        // line 3
        $this->env->getExtension('yii\twig\Extension')->addUses("app/widgets/EForm/EForm");
        echo "
    ";
        // line 4
        $context["form"] = $this->env->getExtension('yii\twig\Extension')->beginWidget("e_form", array("htmlOptions" => array("id" => "restoreForm", "method" => "post", "action" => $this->env->getExtension('yii\twig\Extension')->path(array(0 => "/auth/restore")))));
        // line 11
        echo "
    <div class=\"error-form\"></div>

    <div class=\"form-group\" attribute=\"email\">
        <label for=\"exampleInputEmail1\">";
        // line 15
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Ваш email")), "html", null, true);
        echo "</label>
        <div class=\"input-group\">
            <span class=\"input-group-addon text-light-gray\" id=\"basic-addon1\"><i class=\"fa fa-envelope\"></i></span>
            <input autocomplete=\"off\" type=\"text\" class=\"form-control\" name=\"email\" placeholder=\"Email\"/>
        </div>
        <small id=\"emailHelp\" class=\"form-text text-muted\">";
        // line 20
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "")), "html", null, true);
        echo "</small>
    </div>

    <div class=\"form-group row\" attribute=\"verifyCode\">
        ";
        // line 24
        $this->env->getExtension('yii\twig\Extension')->addUses("app/widgets/ECaptcha/ECaptcha");
        echo "
        ";
        // line 25
        echo $this->env->getExtension('yii\twig\Extension')->widget("e_captcha", array("name" => "verifyCode"));
        echo "
    </div>

    <div class=\"form-group\">
        <input style=\"width:100%;\" type=\"submit\" class=\"text-center btn btn-success\"
               value=\"";
        // line 30
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Восстановить")), "html", null, true);
        echo "\"/>
    </div>

    <div class=\"form-group mt\">
        <a href=\"";
        // line 34
        echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path(array(0 => "auth/registration")), "html", null, true);
        echo "\" style=\"width:100%; cursor:pointer;\" type=\"submit\"
           class=\"btn btn-info text-center\">";
        // line 35
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Регистрация")), "html", null, true);
        echo "</a>
    </div>
    ";
        // line 37
        $this->env->getExtension('yii\twig\Extension')->endWidget("e_form");
        echo "
</div>";
    }

    public function getTemplateName()
    {
        return "restore.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  79 => 37,  74 => 35,  70 => 34,  63 => 30,  55 => 25,  51 => 24,  44 => 20,  36 => 15,  30 => 11,  28 => 4,  24 => 3,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{{ void(this.setTitle(translate('main','Восстановление пароля'))) }}
<div class=\"action-content\">
    {{ use('app/widgets/EForm/EForm') }}
    {% set form = e_form_begin({
    'htmlOptions' : {
    'id' : 'restoreForm',
    'method' : 'post',
    'action' : path(['/auth/restore'])
    }
    }) %}

    <div class=\"error-form\"></div>

    <div class=\"form-group\" attribute=\"email\">
        <label for=\"exampleInputEmail1\">{{ translate('main', 'Ваш email') }}</label>
        <div class=\"input-group\">
            <span class=\"input-group-addon text-light-gray\" id=\"basic-addon1\"><i class=\"fa fa-envelope\"></i></span>
            <input autocomplete=\"off\" type=\"text\" class=\"form-control\" name=\"email\" placeholder=\"Email\"/>
        </div>
        <small id=\"emailHelp\" class=\"form-text text-muted\">{{ translate('main', '') }}</small>
    </div>

    <div class=\"form-group row\" attribute=\"verifyCode\">
        {{ use('app/widgets/ECaptcha/ECaptcha') }}
        {{ e_captcha_widget({'name': 'verifyCode'}) }}
    </div>

    <div class=\"form-group\">
        <input style=\"width:100%;\" type=\"submit\" class=\"text-center btn btn-success\"
               value=\"{{ translate('main','Восстановить') }}\"/>
    </div>

    <div class=\"form-group mt\">
        <a href=\"{{ path(['auth/registration']) }}\" style=\"width:100%; cursor:pointer;\" type=\"submit\"
           class=\"btn btn-info text-center\">{{ translate('main','Регистрация') }}</a>
    </div>
    {{ e_form_end() }}
</div>", "restore.twig", "E:\\dists\\openServer\\OpenServer\\domains\\krw\\protected\\web\\views\\auth\\restore.twig");
    }
}
