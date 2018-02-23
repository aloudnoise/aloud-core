<?php

/* main_header.twig */
class __TwigTemplate_3ee1fac7d7f44ce5bbf2287c9673243963feb76d5d82fea37d2a1093d1fd71ee extends Twig_Template
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
        $context["menu"] = array(0 => array("name" => "news", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Новости")), "url" => call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/news/index", "from" => 0, "return" => 0))), "class" => ""), 1 => array("name" => "polls", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Голосования")), "url" => call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/polls/index", "from" => 0, "return" => 0))), "class" => "", "role" => "admin"), 2 => array("name" => "reports", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Отчеты")), "url" => call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/reports/index", "from" => 0, "return" => 0))), "role" => "base_teacher", "class" => ""), 3 => array("name" => "hr", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "HR")), "url" => call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/hr/users/index", "from" => 0, "return" => 0))), "role" => "SUPER", "class" => ""), 4 => array("name" => "dics", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Настройки системы")), "url" => call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/dics/list", "from" => null))), "role" => "admin", "class" => ""), 5 => array("name" => "admin", "caption" => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Админ")), "url" => $this->env->getExtension('yii\twig\Extension')->path(array(0 => "/cabinet/admin/index", "from" => null)), "role" => "SUPER", "class" => ""));
        // line 44
        echo "<div class=\"sticky-top top-header border-warning\" style=\"border-bottom:2px solid;\">
    <div class=\"row justify-content-center\" style=\"margin:0;\">
        <div class=\"";
        // line 46
        echo twig_escape_filter($this->env, ((call_user_func_array($this->env->getFunction('getDataAttribute')->getCallable(), array("layout_class"))) ? (call_user_func_array($this->env->getFunction('getDataAttribute')->getCallable(), array("layout_class"))) : ("col-xl-11 col-lg-12 col-md-12 col-sm-12")), "html", null, true);
        echo "\">

            <div class=\"row\" style=\"height:60px\">
                ";
        // line 49
        if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "id", array())) {
            // line 50
            echo "                    ";
            $context["current_org"] = call_user_func_array($this->env->getFunction('staticCall')->getCallable(), array("common/models/Organizations", "getCurrentOrganization"));
            // line 51
            echo "                    ";
            if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["current_org"] ?? null), "logo", array())) {
                // line 52
                echo "                        <div class=\"col col-auto align-self-center\">
                            <a href=\"/\"><img style=\"max-height:32px;\" src=\"";
                // line 53
                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["current_org"] ?? null), "logo", array()), "html", null, true);
                echo "\" /></a>
                        </div>
                    ";
            } else {
                // line 56
                echo "                        <div class=\"col col-auto align-self-center pr-5\">
                            ";
                // line 57
                if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "id", array())) {
                    // line 58
                    echo "                                ";
                    $context["current_org"] = call_user_func_array($this->env->getFunction('staticCall')->getCallable(), array("common/models/Organizations", "getCurrentOrganization"));
                    // line 59
                    echo "                                ";
                    if ((twig_length_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "identity", array()), "organizationsList", array())) > 1)) {
                        // line 60
                        echo "                                    <div class=\"btn-group\">
                                        <h5 class=\"text-purple pointer dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            ";
                        // line 62
                        if (($context["current_org"] ?? null)) {
                            // line 63
                            echo "                                                <span title=\"";
                            echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["current_org"] ?? null), "nameByLang", array()), "html", null, true);
                            echo "\" class=\"truncate\" style=\"max-width:250px\">";
                            echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["current_org"] ?? null), "nameByLang", array()), "html", null, true);
                            echo "</span>
                                            ";
                        } else {
                            // line 65
                            echo "                                                ";
                            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Выберите организацию")), "html", null, true);
                            echo "
                                            ";
                        }
                        // line 67
                        echo "                                        </h5>
                                        <div class=\"dropdown-menu\">
                                            ";
                        // line 69
                        if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "can", array(0 => "SUPER"), "method")) {
                            // line 70
                            echo "                                                <a class=\"dropdown-item\" href=\"";
                            echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path(array(0 => "/cabinet/admin/index")), "html", null, true);
                            echo "\">";
                            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Общая админка")), "html", null, true);
                            echo "</a>
                                            ";
                        }
                        // line 72
                        echo "                                            ";
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable(yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "identity", array()), "organizationsList", array()));
                        foreach ($context['_seq'] as $context["_key"] => $context["org"]) {
                            // line 73
                            echo "                                                <a class=\"dropdown-item\" href=\"";
                            echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path(array(0 => "/cabinet/base/index", "oid" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["org"], "id", array()))), "html", null, true);
                            echo "\">";
                            echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["org"], "nameByLang", array()), "html", null, true);
                            echo "</a>
                                            ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['org'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 75
                        echo "                                        </div>
                                    </div>
                                ";
                    } elseif (                    // line 77
($context["current_org"] ?? null)) {
                        // line 78
                        echo "                                    <h5 class=\"text-purple\"><span class=\"truncate\" style=\"max-width:250px\">";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["current_org"] ?? null), "nameByLang", array()), "html", null, true);
                        echo "</span></h5>
                                ";
                    }
                    // line 80
                    echo "                            ";
                }
                // line 81
                echo "                        </div>
                    ";
            }
            // line 83
            echo "                ";
        }
        // line 84
        echo "
                <div class=\"col align-self-center ml-auto\">
                    ";
        // line 86
        if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "id", array())) {
            // line 87
            echo "                        <ul class=\"nav nav-pills\">
                            ";
            // line 88
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["menu"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 89
                echo "                                ";
                if (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "role", array()) == null) || yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "can", array(0 => yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "role", array())), "method"))) {
                    // line 90
                    echo "                                    ";
                    if ((yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "type", array()) == "dropdown")) {
                        // line 91
                        echo "                                        <li class=\"nav-item ";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "class", array()), "html", null, true);
                        echo " relative\">
                                            <a class=\"nav-link\" data-toggle=\"dropdown\" href=\"#\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"><h6>
                                                    ";
                        // line 93
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "caption", array()), "html", null, true);
                        echo "
                                                </h6></a>
                                            <div class=\"dropdown-menu\">
                                                ";
                        // line 96
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable(yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "items", array()));
                        foreach ($context['_seq'] as $context["_key"] => $context["dr_item"]) {
                            // line 97
                            echo "                                                    ";
                            if (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "role", array()) == null) || yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "can", array(0 => yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "role", array())), "method"))) {
                                // line 98
                                echo "                                                        <a class=\"dropdown-item ";
                                echo (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "id", array()) == yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "name", array()))) ? ("text-warning") : ("text-light-gray"));
                                echo "\" href=\"";
                                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "url", array()), "html", null, true);
                                echo "\">
                                                            <h6>";
                                // line 99
                                echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["dr_item"], "caption", array()), "html", null, true);
                                echo "</h6>
                                                        </a>
                                                    ";
                            }
                            // line 102
                            echo "                                                ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['dr_item'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 103
                        echo "                                            </div>
                                        </li>
                                    ";
                    } else {
                        // line 106
                        echo "                                        <li class=\"nav-item ";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "class", array()), "html", null, true);
                        echo "\">
                                            <a class=\"nav-link ";
                        // line 107
                        echo ((((yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "id", array()) == yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "name", array())) || (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "context", array()), "module", array()), "id", array()) == yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "name", array())))) ? ("text-warning") : ("text-light-gray"));
                        echo "\" href=\"";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "url", array()), "html", null, true);
                        echo "\"><h6>";
                        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), $context["item"], "caption", array()), "html", null, true);
                        echo "</h6></a>
                                        </li>
                                    ";
                    }
                    // line 110
                    echo "                                ";
                }
                // line 111
                echo "                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 112
            echo "                        </ul>
                    ";
        }
        // line 114
        echo "                </div>
                <div class=\"col col-auto align-self-center mr-4\">
                    <a target=\"_full\" href=\"";
        // line 116
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/auth/language", "ln" => "ru-RU", "return" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "request", array()), "url", array())))), "html", null, true);
        echo "\">RU</a>
                    |
                    <a target=\"_full\" href=\"";
        // line 118
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/auth/language", "ln" => "kk-KZ", "return" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "request", array()), "url", array())))), "html", null, true);
        echo "\">KZ</a>
                </div>
                <div class=\"col col-auto text-right align-self-center\">
                    ";
        // line 121
        if (yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "user", array()), "isGuest", array())) {
            // line 122
            echo "                        <a target=\"modal\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path("/auth/login"), "html", null, true);
            echo "\"><h5>";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Войти")), "html", null, true);
            echo "</h5></a>
                    ";
        } else {
            // line 124
            echo "                        <a target=\"modal\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path("/auth/logout"), "html", null, true);
            echo "\"><h5>";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Выйти")), "html", null, true);
            echo "</h5></a>
                    ";
        }
        // line 126
        echo "                </div>
            </div>

        </div>
    </div>



    ";
        // line 135
        echo "    ";
        // line 136
        echo "    ";
        // line 137
        echo "    ";
        // line 138
        echo "    ";
        // line 139
        echo "    ";
        // line 140
        echo "    ";
        // line 141
        echo "    ";
        // line 142
        echo "    ";
        // line 143
        echo "    ";
        // line 144
        echo "    ";
        // line 145
        echo "    ";
        // line 146
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
        return array (  286 => 146,  284 => 145,  282 => 144,  280 => 143,  278 => 142,  276 => 141,  274 => 140,  272 => 139,  270 => 138,  268 => 137,  266 => 136,  264 => 135,  254 => 126,  246 => 124,  238 => 122,  236 => 121,  230 => 118,  225 => 116,  221 => 114,  217 => 112,  211 => 111,  208 => 110,  198 => 107,  193 => 106,  188 => 103,  182 => 102,  176 => 99,  169 => 98,  166 => 97,  162 => 96,  156 => 93,  150 => 91,  147 => 90,  144 => 89,  140 => 88,  137 => 87,  135 => 86,  131 => 84,  128 => 83,  124 => 81,  121 => 80,  115 => 78,  113 => 77,  109 => 75,  98 => 73,  93 => 72,  85 => 70,  83 => 69,  79 => 67,  73 => 65,  65 => 63,  63 => 62,  59 => 60,  56 => 59,  53 => 58,  51 => 57,  48 => 56,  42 => 53,  39 => 52,  36 => 51,  33 => 50,  31 => 49,  25 => 46,  21 => 44,  19 => 1,);
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
        role : 'SUPER',
        class : ''
    },
    {
        name : 'dics',
        caption : translate('main','Настройки системы'),
        url : organizationPath({0:'/dics/list', from: null}),
        role : 'admin',
        class : ''
    },
    {
        name : 'admin',
        caption : translate('main','Админ'),
        url : path({0:'/cabinet/admin/index', from: null}),
        role : 'SUPER',
        class : ''
    },
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
                                {% if app.user.identity.organizationsList|length > 1 %}
                                    <div class=\"btn-group\">
                                        <h5 class=\"text-purple pointer dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            {% if current_org %}
                                                <span title=\"{{ current_org.nameByLang }}\" class=\"truncate\" style=\"max-width:250px\">{{ current_org.nameByLang }}</span>
                                            {% else %}
                                                {{ translate('main','Выберите организацию') }}
                                            {% endif %}
                                        </h5>
                                        <div class=\"dropdown-menu\">
                                            {% if app.user.can(\"SUPER\") %}
                                                <a class=\"dropdown-item\" href=\"{{ path({0: '/cabinet/admin/index'}) }}\">{{ translate('main','Общая админка') }}</a>
                                            {% endif %}
                                            {% for org in app.user.identity.organizationsList %}
                                                <a class=\"dropdown-item\" href=\"{{ path({0: '/cabinet/base/index', 'oid': org.id}) }}\">{{ org.nameByLang }}</a>
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

</div>", "main_header.twig", "E:\\dists\\openServer\\OpenServer\\domains\\krw\\protected\\bilimal\\web\\views\\layouts\\main_header.twig");
    }
}
