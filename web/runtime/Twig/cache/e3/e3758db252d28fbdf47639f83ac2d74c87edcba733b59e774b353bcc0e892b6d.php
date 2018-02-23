<?php

/* main_footer.twig */
class __TwigTemplate_e47f1c9591bff903b0f3023e29f1fb99dfe2a6dafa4cf9c125b4e2978bf29142 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"footer d-print-none\">

    <div class=\"color-line\" style=\"height:4px;\">
        <div class=\"bg-info\"></div>
        <div class=\"bg-primary\"></div>
        <div class=\"bg-success-light\"></div>
        <div class=\"bg-success\"></div>
        <div class=\"bg-purple-light\"></div>
        <div class=\"bg-purple\"></div>
        <div class=\"bg-danger-light\"></div>
        <div class=\"bg-danger\"></div>
        <div class=\"bg-warning-light\"></div>
        <div class=\"bg-warning\"></div>
    </div>

    <div class=\"p-3 row justify-content-center\" style=\"height:30px;\">
        <div class=\"text-center col-auto align-self-center\">
            <a target=\"modal\" style=\"text-decoration: underline;\" href=\"";
        // line 18
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/main/updates"))), "html", null, true);
        echo "\" class=\"text-very-light-gray\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Обновления")), "html", null, true);
        echo "</a>
        </div>
        <div class=\"text-center col-auto align-self-center\">
            <a target=\"modal\" style=\"text-decoration: underline;\" href=\"";
        // line 21
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/main/support"))), "html", null, true);
        echo "\" class=\"text-very-light-gray\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Написать в службу поддержки")), "html", null, true);
        echo "</a>
        </div>
    </div>

</div>";
    }

    public function getTemplateName()
    {
        return "main_footer.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 21,  42 => 18,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<div class=\"footer d-print-none\">

    <div class=\"color-line\" style=\"height:4px;\">
        <div class=\"bg-info\"></div>
        <div class=\"bg-primary\"></div>
        <div class=\"bg-success-light\"></div>
        <div class=\"bg-success\"></div>
        <div class=\"bg-purple-light\"></div>
        <div class=\"bg-purple\"></div>
        <div class=\"bg-danger-light\"></div>
        <div class=\"bg-danger\"></div>
        <div class=\"bg-warning-light\"></div>
        <div class=\"bg-warning\"></div>
    </div>

    <div class=\"p-3 row justify-content-center\" style=\"height:30px;\">
        <div class=\"text-center col-auto align-self-center\">
            <a target=\"modal\" style=\"text-decoration: underline;\" href=\"{{ organizationPath(['/main/updates']) }}\" class=\"text-very-light-gray\">{{ translate('main','Обновления') }}</a>
        </div>
        <div class=\"text-center col-auto align-self-center\">
            <a target=\"modal\" style=\"text-decoration: underline;\" href=\"{{ organizationPath(['/main/support']) }}\" class=\"text-very-light-gray\">{{ translate('main','Написать в службу поддержки') }}</a>
        </div>
    </div>

</div>", "main_footer.twig", "E:\\dists\\openServer\\OpenServer\\domains\\krw\\protected\\web\\views\\layouts\\main_footer.twig");
    }
}
