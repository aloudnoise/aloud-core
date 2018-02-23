<?php

/* main_header.twig */
class __TwigTemplate_d8e5ca81b8f21996d2041a205b7d0480b61344d1f1302ab43ccae75bede20227 extends Twig_Template
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
        $context["menu"] = array(0 => array("name" => "news", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Новости")), "url" => call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/news/index", "from" => 0, "return" => 0))), "class" => ""), 1 => array("name" => "forum", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Форум")), "url" => ((yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(),         // line 11
($context["app"] ?? null), "params", array()), "forum_host", array()) . "?uid=") . yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "identity", array()), "getHash", array(), "method")), "class" => ""), 2 => array("name" => "polls", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Голосования")), "url" => call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/polls/index", "from" => 0, "return" => 0))), "class" => "", "role" => "admin"), 3 => array("name" => "reports", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Отчеты")), "url" => call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/reports/index", "from" => 0, "return" => 0))), "role" => "base_teacher", "class" => ""), 4 => array("name" => "hr", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "HR")), "url" => call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/hr/users/index", "from" => 0, "return" => 0))), "role" => "specialist", "class" => ""), 5 => array("name" => "dics", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Настройки системы")), "url" => call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/dics/list", "from" => null))), "role" => "admin", "class" => ""));
        // line 43
        echo "<div class=\"sticky-top top-header border-warning\" style=\"border-bottom:2px solid;\">
    <div class=\"row justify-content-center\" style=\"margin:0;\">
        <div class=\"";
        // line 45
        echo twig_escape_filter($this->env, ((call_user_func_array($this->env->getFunction('getDataAttribute')->getCallable(), array("layout_class"))) ? (call_user_func_array($this->env->getFunction('getDataAttribute')->getCallable(), array("layout_class"))) : ("col-xl-11 col-lg-12 col-md-12 col-sm-12")), "html", null, true);
        echo "\">

            <div class=\"row\" style=\"height:60px\">
                ";
        // line 48
        if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "id", array())) {
            // line 49
            echo "                    ";
            $context["current_org"] = call_user_func_array($this->env->getFunction('staticCall')->getCallable(), array("common/models/Organizations", "getCurrentOrganization"));
            // line 50
            echo "                    ";
            if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["current_org"] ?? null), "logo", array())) {
                // line 51
                echo "                        <div class=\"col col-auto align-self-center\">
                            <a href=\"/\"><img style=\"max-height:32px;\" src=\"";
                // line 52
                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["current_org"] ?? null), "logo", array()), "html", null, true);
                echo "\" /></a>
                        </div>
                    ";
            } else {
                // line 55
                echo "                        <div class=\"col col-auto align-self-center pr-5\">
                            ";
                // line 56
                if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "id", array())) {
                    // line 57
                    echo "                                ";
                    $context["current_org"] = call_user_func_array($this->env->getFunction('staticCall')->getCallable(), array("common/models/Organizations", "getCurrentOrganization"));
                    // line 58
                    echo "                                ";
                    if ((twig_length_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "identity", array()), "organizations", array())) > 1)) {
                        // line 59
                        echo "                                    <div class=\"btn-group\">
                                        <h5 class=\"text-purple pointer dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            ";
                        // line 61
                        if (($context["current_org"] ?? null)) {
                            // line 62
                            echo "                                                <span class=\"truncate\" style=\"max-width:250px\">";
                            echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["current_org"] ?? null), "nameByLang", array()), "html", null, true);
                            echo "</span>
                                            ";
                        } else {
                            // line 64
                            echo "                                                ";
                            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Выберите организацию")), "html", null, true);
                            echo "
                                            ";
                        }
                        // line 66
                        echo "                                        </h5>
                                        <div class=\"dropdown-menu\">
                                            ";
                        // line 68
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable(yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "identity", array()), "organizations", array()));
                        foreach ($context['_seq'] as $context["_key"] => $context["org"]) {
                            // line 69
                            echo "                                                <a class=\"dropdown-item\" href=\"";
                            echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path(array(0 => "/cabinet/base/index", "oid" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["org"], "organization_id", array()))), "html", null, true);
                            echo "\">";
                            echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["org"], "organization", array()), "nameByLang", array()), "html", null, true);
                            echo "</a>
                                            ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['org'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 71
                        echo "                                        </div>
                                    </div>
                                ";
                    } elseif (                    // line 73
($context["current_org"] ?? null)) {
                        // line 74
                        echo "                                    <h5 class=\"text-purple\"><span class=\"truncate\" style=\"max-width:250px\">";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["current_org"] ?? null), "nameByLang", array()), "html", null, true);
                        echo "</span></h5>
                                ";
                    }
                    // line 76
                    echo "                            ";
                }
                // line 77
                echo "                        </div>
                    ";
            }
            // line 79
            echo "                ";
        }
        // line 80
        echo "
                <div class=\"col align-self-center ml-auto\">
                    ";
        // line 82
        if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "id", array())) {
            // line 83
            echo "                    <ul class=\"nav nav-pills\">
                        ";
            // line 84
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["menu"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 85
                echo "                            ";
                if (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "role", array()) == null) || yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "can", array(0 => yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "role", array())), "method"))) {
                    // line 86
                    echo "                                ";
                    if ((yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "type", array()) == "dropdown")) {
                        // line 87
                        echo "                                    <li class=\"nav-item ";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "class", array()), "html", null, true);
                        echo " relative\">
                                        <a class=\"nav-link\" data-toggle=\"dropdown\" href=\"#\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"><h6>
                                                ";
                        // line 89
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "caption", array()), "html", null, true);
                        echo "
                                            </h6></a>
                                        <div class=\"dropdown-menu\">
                                            ";
                        // line 92
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable(yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "items", array()));
                        foreach ($context['_seq'] as $context["_key"] => $context["dr_item"]) {
                            // line 93
                            echo "                                                ";
                            if (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "role", array()) == null) || yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "can", array(0 => yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "role", array())), "method"))) {
                                // line 94
                                echo "                                                <a class=\"dropdown-item ";
                                echo (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "id", array()) == yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "name", array()))) ? ("text-warning") : ("text-light-gray"));
                                echo "\" href=\"";
                                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "url", array()), "html", null, true);
                                echo "\">
                                                    <h6>";
                                // line 95
                                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "caption", array()), "html", null, true);
                                echo "</h6>
                                                </a>
                                                ";
                            }
                            // line 98
                            echo "                                            ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['dr_item'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 99
                        echo "                                        </div>
                                    </li>
                                ";
                    } else {
                        // line 102
                        echo "                                    <li class=\"nav-item ";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "class", array()), "html", null, true);
                        echo "\">
                                        <a class=\"nav-link ";
                        // line 103
                        echo ((((yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "id", array()) == yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "name", array())) || (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "module", array()), "id", array()) == yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "name", array())))) ? ("text-warning") : ("text-light-gray"));
                        echo "\" href=\"";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "url", array()), "html", null, true);
                        echo "\"><h6>";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "caption", array()), "html", null, true);
                        echo "</h6></a>
                                    </li>
                                ";
                    }
                    // line 106
                    echo "                            ";
                }
                // line 107
                echo "                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 108
            echo "                    </ul>
                    ";
        }
        // line 110
        echo "                </div>
                ";
        // line 111
        $context["context"] = (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "module", array()), "id", array()) == "app")) ? (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "id", array())) : (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "module", array()), "id", array())));
        // line 112
        echo "                ";
        if (call_user_func_array($this->env->getFunction('staticCall')->getCallable(), array("common/models/Instructions", "getInstruction", array(0 =>         // line 113
($context["context"] ?? null))))) {
            // line 115
            echo "                <div class=\"col col-auto align-self-center mr-2\">
                    <a style=\"font-size:2rem;\" class=\"text-warning\" href=\"";
            // line 116
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/main/download", "instruction" => ($context["context"] ?? null)))), "html", null, true);
            echo "\"><i class=\"fa fa-question-circle-o\"></i></a>
                </div>
                ";
        }
        // line 119
        echo "
                <div class=\"col col-auto align-self-center mr-4\">
                    <a target=\"_full\" href=\"";
        // line 121
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/auth/language", "ln" => "ru-RU", "return" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "request", array()), "url", array())))), "html", null, true);
        echo "\">RU</a>
                    |
                    <a target=\"_full\" href=\"";
        // line 123
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/auth/language", "ln" => "kk-KZ", "return" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "request", array()), "url", array())))), "html", null, true);
        echo "\">KZ</a>
                </div>
                <div class=\"col col-auto text-right align-self-center\">
                    ";
        // line 126
        if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "isGuest", array())) {
            // line 127
            echo "                        <a target=\"modal\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path("/auth/login"), "html", null, true);
            echo "\"><h5>";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Войти")), "html", null, true);
            echo "</h5></a>
                    ";
        } else {
            // line 129
            echo "                        <a target=\"modal\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path("/auth/logout"), "html", null, true);
            echo "\"><h5>";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Выйти")), "html", null, true);
            echo "</h5></a>
                    ";
        }
        // line 131
        echo "                </div>
            </div>

        </div>
    </div>



    ";
        // line 140
        echo "        ";
        // line 141
        echo "        ";
        // line 142
        echo "        ";
        // line 143
        echo "        ";
        // line 144
        echo "        ";
        // line 145
        echo "        ";
        // line 146
        echo "        ";
        // line 147
        echo "        ";
        // line 148
        echo "        ";
        // line 149
        echo "        ";
        // line 150
        echo "    ";
        // line 151
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
        return array (  292 => 151,  290 => 150,  288 => 149,  286 => 148,  284 => 147,  282 => 146,  280 => 145,  278 => 144,  276 => 143,  274 => 142,  272 => 141,  270 => 140,  260 => 131,  252 => 129,  244 => 127,  242 => 126,  236 => 123,  231 => 121,  227 => 119,  221 => 116,  218 => 115,  216 => 113,  214 => 112,  212 => 111,  209 => 110,  205 => 108,  199 => 107,  196 => 106,  186 => 103,  181 => 102,  176 => 99,  170 => 98,  164 => 95,  157 => 94,  154 => 93,  150 => 92,  144 => 89,  138 => 87,  135 => 86,  132 => 85,  128 => 84,  125 => 83,  123 => 82,  119 => 80,  116 => 79,  112 => 77,  109 => 76,  103 => 74,  101 => 73,  97 => 71,  86 => 69,  82 => 68,  78 => 66,  72 => 64,  66 => 62,  64 => 61,  60 => 59,  57 => 58,  54 => 57,  52 => 56,  49 => 55,  43 => 52,  40 => 51,  37 => 50,  34 => 49,  32 => 48,  26 => 45,  22 => 43,  20 => 11,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% set menu = [
    {
        name : 'news',
        caption : translate('main','Новости'),
        url : organizationPath({0:'/news/index', from: 0, return: 0}),
        class : ''
    },
    {
        name : 'forum',
        caption : translate('main','Форум'),
        url : app.params.forum_host ~ '?uid=' ~ app.user.identity.getHash(),
        class : ''
    },
    {
        name : 'polls',
        caption : translate('main','Голосования'),
        url : organizationPath({0:'/polls/index', from: 0, return: 0}),
        class : '',
        role: 'admin'
    },
    {
        name : 'reports',
        caption : translate('main','Отчеты'),
        url : organizationPath({0:'/reports/index', from: 0, return: 0}),
        role : 'base_teacher',
        class : ''
    },
    {
        name : 'hr',
        caption : translate('main','HR'),
        url : organizationPath({0:'/hr/users/index', from: 0, return: 0}),
        role : 'specialist',
        class : ''
    },
    {
        name : 'dics',
        caption : translate('main','Настройки системы'),
        url : organizationPath({0:'/dics/list', from: null}),
        role : 'admin',
        class : ''
    }
] %}
<div class=\"sticky-top top-header border-warning\" style=\"border-bottom:2px solid;\">
    <div class=\"row justify-content-center\" style=\"margin:0;\">
        <div class=\"{{ getDataAttribute('layout_class') ? getDataAttribute('layout_class') : \"col-xl-11 col-lg-12 col-md-12 col-sm-12\" }}\">

            <div class=\"row\" style=\"height:60px\">
                {% if app.user.id %}
                    {% set current_org = staticCall('common/models/Organizations', 'getCurrentOrganization') %}
                    {% if current_org.logo %}
                        <div class=\"col col-auto align-self-center\">
                            <a href=\"/\"><img style=\"max-height:32px;\" src=\"{{ current_org.logo }}\" /></a>
                        </div>
                    {% else %}
                        <div class=\"col col-auto align-self-center pr-5\">
                            {% if app.user.id %}
                                {% set current_org = staticCall('common/models/Organizations', 'getCurrentOrganization') %}
                                {% if app.user.identity.organizations|length > 1 %}
                                    <div class=\"btn-group\">
                                        <h5 class=\"text-purple pointer dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            {% if current_org %}
                                                <span class=\"truncate\" style=\"max-width:250px\">{{ current_org.nameByLang }}</span>
                                            {% else %}
                                                {{ translate('main','Выберите организацию') }}
                                            {% endif %}
                                        </h5>
                                        <div class=\"dropdown-menu\">
                                            {% for org in app.user.identity.organizations %}
                                                <a class=\"dropdown-item\" href=\"{{ path({0: '/cabinet/base/index', 'oid': org.organization_id}) }}\">{{ org.organization.nameByLang }}</a>
                                            {% endfor %}
                                        </div>
                                    </div>
                                {% elseif current_org %}
                                    <h5 class=\"text-purple\"><span class=\"truncate\" style=\"max-width:250px\">{{ current_org.nameByLang }}</span></h5>
                                {% endif %}
                            {% endif %}
                        </div>
                    {% endif %}
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
                {% set context = this.context.module.id == 'app' ? this.context.id : this.context.module.id %}
                {% if staticCall(\"common/models/Instructions\", \"getInstruction\", [
                    context
                ]) %}
                <div class=\"col col-auto align-self-center mr-2\">
                    <a style=\"font-size:2rem;\" class=\"text-warning\" href=\"{{ organizationPath({0: '/main/download', 'instruction' : context}) }}\"><i class=\"fa fa-question-circle-o\"></i></a>
                </div>
                {% endif %}

                <div class=\"col col-auto align-self-center mr-4\">
                    <a target=\"_full\" href=\"{{ organizationPath({0: '/auth/language', ln : \"ru-RU\", return: app.request.url}) }}\">RU</a>
                    |
                    <a target=\"_full\" href=\"{{ organizationPath({0: '/auth/language', ln : \"kk-KZ\", return: app.request.url}) }}\">KZ</a>
                </div>
                <div class=\"col col-auto text-right align-self-center\">
                    {% if app.user.isGuest %}
                        <a target=\"modal\" href=\"{{ path('/auth/login') }}\"><h5>{{ translate('main','Войти') }}</h5></a>
                    {% else %}
                        <a target=\"modal\" href=\"{{ path('/auth/logout') }}\"><h5>{{ translate('main','Выйти') }}</h5></a>
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

</div>", "main_header.twig", "E:\\dists\\openServer\\OpenServer\\domains\\krw\\protected\\themes\\krw\\web\\views\\layouts\\main_header.twig");
    }
}
