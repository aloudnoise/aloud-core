<?php

/* main_header.twig */
class __TwigTemplate_40b50913ea1f451288122d9d2d51ca27c46cfa32bb6f3b81309169c2ddeddc6c extends Twig_Template
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
        echo "<div class=\"main-header sticky-top top-header border-danger bg-danger\" style=\"border-bottom:2px solid;\">
    <div class=\"row justify-content-center\" style=\"margin:0;\">
        <div class=\"";
        // line 3
        echo twig_escape_filter($this->env, ((call_user_func_array($this->env->getFunction('getDataAttribute')->getCallable(), array("layout_class"))) ? (call_user_func_array($this->env->getFunction('getDataAttribute')->getCallable(), array("layout_class"))) : ("col-12")), "html", null, true);
        echo "\">

            <div class=\"row\" style=\"height:60px\">

                <div class=\"col-auto d-sm-block d-lg-none align-self-center pl-3\">
                    <a style=\"margin-top:-4px; display:inline-block; font-size:1.8rem;\" class=\"pointer text-white show-menu\"><i class=\"fa fa-bars\"></i></a>
                </div>

                ";
        // line 11
        if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "id", array())) {
            // line 12
            echo "                    ";
            $context["current_org"] = call_user_func_array($this->env->getFunction('staticCall')->getCallable(), array("common/models/Organizations", "getCurrentOrganization"));
            // line 13
            echo "                    <div class=\"col-auto align-self-center pr-5 pl-3\">
                        ";
            // line 14
            $context["current_org"] = call_user_func_array($this->env->getFunction('staticCall')->getCallable(), array("common/models/Organizations", "getCurrentOrganization"));
            // line 15
            echo "                        ";
            if ((twig_length_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "identity", array()), "organizationsList", array())) > 1)) {
                // line 16
                echo "                            <div class=\"btn-group\">
                                <h5 class=\"mb-0 text-uppercase text-white pointer dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                    ";
                // line 18
                if (($context["current_org"] ?? null)) {
                    // line 19
                    echo "                                        <span class=\"truncate\" style=\"max-width:700px\">";
                    echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["current_org"] ?? null), "nameByLang", array()), "html", null, true);
                    echo "</span>
                                    ";
                } else {
                    // line 21
                    echo "                                        ";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Выберите организацию")), "html", null, true);
                    echo "
                                    ";
                }
                // line 23
                echo "                                </h5>
                                <div class=\"dropdown-menu\">
                                    ";
                // line 25
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "identity", array()), "organizationsList", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["org"]) {
                    // line 26
                    echo "                                        <a class=\"dropdown-item\" href=\"";
                    echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path(array(0 => "/cabinet/base/change-organization", "organization_id" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["org"], "id", array()))), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["org"], "nameByLang", array()), "html", null, true);
                    echo "</a>
                                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['org'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 28
                echo "                                </div>
                            </div>
                        ";
            } elseif (            // line 30
($context["current_org"] ?? null)) {
                // line 31
                echo "                            <h5 class=\"mb-0 text-uppercase text-white\"><span class=\"truncate\" style=\"max-width:700px\">";
                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["current_org"] ?? null), "nameByLang", array()), "html", null, true);
                echo "</span></h5>
                        ";
            }
            // line 33
            echo "                    </div>
                ";
        }
        // line 35
        echo "
                <div class=\"col align-self-center ml-auto\">
                    ";
        // line 37
        if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "id", array())) {
            // line 38
            echo "                        <ul class=\"nav nav-pills\">
                            ";
            // line 39
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["menu"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 40
                echo "                                ";
                if (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "role", array()) == null) || yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "can", array(0 => yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "role", array())), "method"))) {
                    // line 41
                    echo "                                    ";
                    if ((yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "type", array()) == "dropdown")) {
                        // line 42
                        echo "                                        <li class=\"nav-item ";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "class", array()), "html", null, true);
                        echo " relative\">
                                            <a class=\"nav-link\" data-toggle=\"dropdown\" href=\"#\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"><h6>
                                                    ";
                        // line 44
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "caption", array()), "html", null, true);
                        echo "
                                                </h6></a>
                                            <div class=\"dropdown-menu\">
                                                ";
                        // line 47
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable(yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "items", array()));
                        foreach ($context['_seq'] as $context["_key"] => $context["dr_item"]) {
                            // line 48
                            echo "                                                    ";
                            if (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "role", array()) == null) || yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "can", array(0 => yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "role", array())), "method"))) {
                                // line 49
                                echo "                                                        <a class=\"dropdown-item ";
                                echo (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "id", array()) == yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "name", array()))) ? ("text-warning") : ("text-light-gray"));
                                echo "\" href=\"";
                                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "url", array()), "html", null, true);
                                echo "\">
                                                            <h6>";
                                // line 50
                                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "caption", array()), "html", null, true);
                                echo "</h6>
                                                        </a>
                                                    ";
                            }
                            // line 53
                            echo "                                                ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['dr_item'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 54
                        echo "                                            </div>
                                        </li>
                                    ";
                    } else {
                        // line 57
                        echo "                                        <li class=\"nav-item ";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "class", array()), "html", null, true);
                        echo "\">
                                            <a class=\"nav-link ";
                        // line 58
                        echo ((((yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "id", array()) == yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "name", array())) || (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "module", array()), "id", array()) == yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "name", array())))) ? ("text-warning") : ("text-light-gray"));
                        echo "\" href=\"";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "url", array()), "html", null, true);
                        echo "\"><h6>";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "caption", array()), "html", null, true);
                        echo "</h6></a>
                                        </li>
                                    ";
                    }
                    // line 61
                    echo "                                ";
                }
                // line 62
                echo "                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 63
            echo "                        </ul>
                    ";
        }
        // line 65
        echo "                </div>

                <div class=\"col-auto align-self-center mr-2\">
                    ";
        // line 68
        $this->env->getExtension('yii\twig\Extension')->addUses("app/widgets/ENotifications/ENotifications");
        echo "
                    ";
        // line 69
        echo $this->env->getExtension('yii\twig\Extension')->widget("e_notifications");
        echo "
                </div>

                ";
        // line 72
        $context["context"] = (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "module", array()), "id", array()) == "app")) ? (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "id", array())) : (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "module", array()), "id", array())));
        // line 73
        echo "                ";
        if (call_user_func_array($this->env->getFunction('staticCall')->getCallable(), array("common/models/Instructions", "getInstruction", array(0 =>         // line 74
($context["context"] ?? null))))) {
            // line 76
            echo "                    <div class=\"col-auto align-self-center mr-2\">
                        <a style=\"font-size:1.8rem;\" class=\"text-white\" href=\"";
            // line 77
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/main/download", "instruction" => ($context["context"] ?? null)))), "html", null, true);
            echo "\"><i class=\"fa fa-question-circle\"></i></a>
                    </div>
                ";
        }
        // line 80
        echo "
                <div class=\"text-white col-auto align-self-center mr-4\">
                    <a class=\"text-white\" target=\"_full\" href=\"";
        // line 82
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/auth/language", "ln" => "ru-RU", "return" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "request", array()), "url", array())))), "html", null, true);
        echo "\">RU</a>
                    |
                    <a class=\"text-white\" target=\"_full\" href=\"";
        // line 84
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/auth/language", "ln" => "kk-KZ", "return" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "request", array()), "url", array())))), "html", null, true);
        echo "\">KZ</a>
                </div>
                <div class=\"col-auto text-right align-self-center pr-3\">
                    ";
        // line 87
        if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "isGuest", array())) {
            // line 88
            echo "                    ";
        } else {
            // line 89
            echo "                        <a class=\"font-bold text-white\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path("/auth/logout"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Выйти")), "html", null, true);
            echo "</a>
                    ";
        }
        // line 91
        echo "                </div>
            </div>

        </div>
    </div>



    ";
        // line 100
        echo "    ";
        // line 101
        echo "    ";
        // line 102
        echo "    ";
        // line 103
        echo "    ";
        // line 104
        echo "    ";
        // line 105
        echo "    ";
        // line 106
        echo "    ";
        // line 107
        echo "    ";
        // line 108
        echo "    ";
        // line 109
        echo "    ";
        // line 110
        echo "    ";
        // line 111
        echo "
</div>";
    }

    public function getTemplateName()
    {
        return "main_header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  284 => 111,  282 => 110,  280 => 109,  278 => 108,  276 => 107,  274 => 106,  272 => 105,  270 => 104,  268 => 103,  266 => 102,  264 => 101,  262 => 100,  252 => 91,  244 => 89,  241 => 88,  239 => 87,  233 => 84,  228 => 82,  224 => 80,  218 => 77,  215 => 76,  213 => 74,  211 => 73,  209 => 72,  203 => 69,  199 => 68,  194 => 65,  190 => 63,  184 => 62,  181 => 61,  171 => 58,  166 => 57,  161 => 54,  155 => 53,  149 => 50,  142 => 49,  139 => 48,  135 => 47,  129 => 44,  123 => 42,  120 => 41,  117 => 40,  113 => 39,  110 => 38,  108 => 37,  104 => 35,  100 => 33,  94 => 31,  92 => 30,  88 => 28,  77 => 26,  73 => 25,  69 => 23,  63 => 21,  57 => 19,  55 => 18,  51 => 16,  48 => 15,  46 => 14,  43 => 13,  40 => 12,  38 => 11,  27 => 3,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<div class=\"main-header sticky-top top-header border-danger bg-danger\" style=\"border-bottom:2px solid;\">
    <div class=\"row justify-content-center\" style=\"margin:0;\">
        <div class=\"{{ getDataAttribute('layout_class') ? getDataAttribute('layout_class') : \"col-12\" }}\">

            <div class=\"row\" style=\"height:60px\">

                <div class=\"col-auto d-sm-block d-lg-none align-self-center pl-3\">
                    <a style=\"margin-top:-4px; display:inline-block; font-size:1.8rem;\" class=\"pointer text-white show-menu\"><i class=\"fa fa-bars\"></i></a>
                </div>

                {% if app.user.id %}
                    {% set current_org = staticCall('common/models/Organizations', 'getCurrentOrganization') %}
                    <div class=\"col-auto align-self-center pr-5 pl-3\">
                        {% set current_org = staticCall('common/models/Organizations', 'getCurrentOrganization') %}
                        {% if app.user.identity.organizationsList|length > 1 %}
                            <div class=\"btn-group\">
                                <h5 class=\"mb-0 text-uppercase text-white pointer dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                    {% if current_org %}
                                        <span class=\"truncate\" style=\"max-width:700px\">{{ current_org.nameByLang }}</span>
                                    {% else %}
                                        {{ translate('main','Выберите организацию') }}
                                    {% endif %}
                                </h5>
                                <div class=\"dropdown-menu\">
                                    {% for org in app.user.identity.organizationsList %}
                                        <a class=\"dropdown-item\" href=\"{{ path({0: '/cabinet/base/change-organization', 'organization_id': org.id}) }}\">{{ org.nameByLang }}</a>
                                    {% endfor %}
                                </div>
                            </div>
                        {% elseif current_org %}
                            <h5 class=\"mb-0 text-uppercase text-white\"><span class=\"truncate\" style=\"max-width:700px\">{{ current_org.nameByLang }}</span></h5>
                        {% endif %}
                    </div>
                {% endif %}

                <div class=\"col align-self-center ml-auto\">
                    {% if app.user.id %}
                        <ul class=\"nav nav-pills\">
                            {% for item in menu %}
                                {% if (item.role == null or app.user.can(item.role)) %}
                                    {% if (item.type == 'dropdown') %}
                                        <li class=\"nav-item {{ item.class }} relative\">
                                            <a class=\"nav-link\" data-toggle=\"dropdown\" href=\"#\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"><h6>
                                                    {{ item.caption }}
                                                </h6></a>
                                            <div class=\"dropdown-menu\">
                                                {% for dr_item in item.items %}
                                                    {% if (dr_item.role == null or app.user.can(dr_item.role)) %}
                                                        <a class=\"dropdown-item {{ this.context.id == dr_item.name  ? \"text-warning\" : \"text-light-gray\" }}\" href=\"{{ dr_item.url }}\">
                                                            <h6>{{ dr_item.caption }}</h6>
                                                        </a>
                                                    {% endif %}
                                                {% endfor %}
                                            </div>
                                        </li>
                                    {% else %}
                                        <li class=\"nav-item {{ item.class }}\">
                                            <a class=\"nav-link {{ ((this.context.id == item.name) or (this.context.module.id == item.name)) ? 'text-warning' : 'text-light-gray' }}\" href=\"{{ item.url }}\"><h6>{{ item.caption }}</h6></a>
                                        </li>
                                    {% endif %}
                                {% endif %}
                            {% endfor %}
                        </ul>
                    {% endif %}
                </div>

                <div class=\"col-auto align-self-center mr-2\">
                    {{ use('app/widgets/ENotifications/ENotifications') }}
                    {{ e_notifications_widget() }}
                </div>

                {% set context = this.context.module.id == 'app' ? this.context.id : this.context.module.id %}
                {% if staticCall(\"common/models/Instructions\", \"getInstruction\", [
                    context
                ]) %}
                    <div class=\"col-auto align-self-center mr-2\">
                        <a style=\"font-size:1.8rem;\" class=\"text-white\" href=\"{{ organizationPath({0: '/main/download', 'instruction' : context}) }}\"><i class=\"fa fa-question-circle\"></i></a>
                    </div>
                {% endif %}

                <div class=\"text-white col-auto align-self-center mr-4\">
                    <a class=\"text-white\" target=\"_full\" href=\"{{ organizationPath({0: '/auth/language', ln : \"ru-RU\", return: app.request.url}) }}\">RU</a>
                    |
                    <a class=\"text-white\" target=\"_full\" href=\"{{ organizationPath({0: '/auth/language', ln : \"kk-KZ\", return: app.request.url}) }}\">KZ</a>
                </div>
                <div class=\"col-auto text-right align-self-center pr-3\">
                    {% if app.user.isGuest %}
                    {% else %}
                        <a class=\"font-bold text-white\" href=\"{{ path('/auth/logout') }}\">{{ translate('main','Выйти') }}</a>
                    {% endif %}
                </div>
            </div>

        </div>
    </div>



    {#<div class=\"color-line\">#}
    {#<div class=\"bg-info\"></div>#}
    {#<div class=\"bg-primary\"></div>#}
    {#<div class=\"bg-success-light\"></div>#}
    {#<div class=\"bg-success\"></div>#}
    {#<div class=\"bg-purple-light\"></div>#}
    {#<div class=\"bg-purple\"></div>#}
    {#<div class=\"bg-danger-light\"></div>#}
    {#<div class=\"bg-danger\"></div>#}
    {#<div class=\"bg-warning-light\"></div>#}
    {#<div class=\"bg-warning\"></div>#}
    {#</div>#}

</div>", "main_header.twig", "E:\\dists\\openServer\\OpenServer\\domains\\krw\\protected\\web\\views\\layouts\\main_header.twig");
    }
}
