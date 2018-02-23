<?php

/* index.twig */
class __TwigTemplate_f0bbc00b981a4716058979345951651ddd9e7d1069f723795982e008c4aed8af extends Twig_Template
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
        echo "<div class=\"action-content\"> <!-- Обязательный тег в каждом action, для того чтобы action в бэкбоне определял область видимости -->

    <!-- шаблон на бекбоне -->
    <script type=\"text/template\" id=\"column_info_template\">
        <div class=\"column-info-item\">
            one_attr : <%=data.model.get(\"one_attr\")%> , two_attr : <%=data.model.get(\"two_attr\")%> , three_attr : <%=data.model.get(\"three_attr\")%>
        </div>
    </script>

    <div class=\"column-info-content\"></div>

    <div class=\"row justify-content-end\">
        <div class=\"col col-auto\">
            <a href=\"";
        // line 14
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/example/add"))), "html", null, true);
        echo "\" class=\"btn btn-primary\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Добавить запись")), "html", null, true);
        echo "</a>
            <a href=\"";
        // line 15
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/example/add"))), "html", null, true);
        echo "\" target=\"modal\" class=\"ml-5 pull-right btn btn-success\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Добавить запись через модалку")), "html", null, true);
        echo "</a>
        </div>
    </div>


    <table class=\"table table-bordered mt-5\">
    ";
        // line 21
        $context["list"] = yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["provider"] ?? null), "getModels", array(), "method");
        // line 22
        echo "    ";
        if (($context["list"] ?? null)) {
            // line 23
            echo "        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["list"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 24
                echo "            <tr>
                <td>";
                // line 25
                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "name", array()), "html", null, true);
                echo "</td>
                <td>one_attr: ";
                // line 26
                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "one_attr", array()), "html", null, true);
                echo ", two_attr: ";
                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "two_attr", array()), "html", null, true);
                echo ", three_attr: ";
                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "three_attr", array()), "html", null, true);
                echo "</td>
            </tr>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 29
            echo "    ";
        }
        // line 30
        echo "    </table>

    ";
        // line 32
        $this->env->getExtension('yii\twig\Extension')->addUses("app/widgets/EPager/EPager");
        echo "
    ";
        // line 33
        echo $this->env->getExtension('yii\twig\Extension')->widget("e_pager", array("pagination" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["provider"] ?? null), "pagination", array())));
        echo "

</div>";
    }

    public function getTemplateName()
    {
        return "index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  92 => 33,  88 => 32,  84 => 30,  81 => 29,  68 => 26,  64 => 25,  61 => 24,  56 => 23,  53 => 22,  51 => 21,  40 => 15,  34 => 14,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<div class=\"action-content\"> <!-- Обязательный тег в каждом action, для того чтобы action в бэкбоне определял область видимости -->

    <!-- шаблон на бекбоне -->
    <script type=\"text/template\" id=\"column_info_template\">
        <div class=\"column-info-item\">
            one_attr : <%=data.model.get(\"one_attr\")%> , two_attr : <%=data.model.get(\"two_attr\")%> , three_attr : <%=data.model.get(\"three_attr\")%>
        </div>
    </script>

    <div class=\"column-info-content\"></div>

    <div class=\"row justify-content-end\">
        <div class=\"col col-auto\">
            <a href=\"{{ organizationPath({0: '/example/add'}) }}\" class=\"btn btn-primary\">{{ translate('main', 'Добавить запись') }}</a>
            <a href=\"{{ organizationPath({0: '/example/add'}) }}\" target=\"modal\" class=\"ml-5 pull-right btn btn-success\">{{ translate('main', 'Добавить запись через модалку') }}</a>
        </div>
    </div>


    <table class=\"table table-bordered mt-5\">
    {% set list = provider.getModels() %}
    {% if list %}
        {% for item in list %}
            <tr>
                <td>{{ item.name }}</td>
                <td>one_attr: {{ item.one_attr }}, two_attr: {{ item.two_attr }}, three_attr: {{ item.three_attr }}</td>
            </tr>
        {% endfor %}
    {% endif %}
    </table>

    {{ use('app/widgets/EPager/EPager') }}
    {{ e_pager_widget({'pagination': provider.pagination}) }}

</div>", "index.twig", "E:\\dists\\openServer\\OpenServer\\domains\\krw\\protected\\web\\views\\example\\index.twig");
    }
}
