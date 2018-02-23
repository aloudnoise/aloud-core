<?php

/* login.twig */
class __TwigTemplate_6cda2c35d52bbb22b3396aa3075c46fc6703dd0893fa8ded59bb61a368ef77e2 extends Twig_Template
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
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('void')->getCallable(), array(yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "setTitle", array(0 => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Логин"))), "method"))), "html", null, true);
        echo "
";
        // line 4
        echo "<div class=\"action-content h-100\">
    <div class=\"row justify-content-center h-100\">
        <div class=\"align-self-center\" style=\"width:370px; margin-top:-60px;\">
            <div class=\"white-block\">
                ";
        // line 8
        $this->env->getExtension('yii\twig\Extension')->addUses("app/widgets/EForm/EForm");
        echo "
                ";
        // line 9
        $context["form"] = $this->env->getExtension('yii\twig\Extension')->beginWidget("e_form", array("htmlOptions" => array("id" => "loginForm", "method" => "post", "action" => $this->env->getExtension('yii\twig\Extension')->path(array(0 => "/auth/login")))));
        // line 16
        echo "
                <div class=\"auth-background-block background-block bg-info border-warning\" style=\"height:170px; border-top:5px solid; margin:-20px -20px 0 -20px;\">
                    <div class=\"block-bg\"></div>
                    <div class=\"inner h-100 px-3\">
                        <div class=\"row h-100\" style=\"margin-top:-6px;\">
                            <div class=\"col-auto align-self-center text-white\">
                                <a class=\"";
        // line 22
        echo (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "language", array()) == "ru-RU")) ? ("text-white") : (""));
        echo "\" target=\"_full\" href=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/auth/language", "ln" => "ru-RU", "return" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "request", array()), "url", array())))), "html", null, true);
        echo "\">RU</a>
                                |
                                <a class=\"";
        // line 24
        echo (((yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "language", array()) == "ru-RU")) ? ("text-white") : (""));
        echo "\" target=\"_full\" href=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/auth/language", "ln" => "kk-KZ", "return" => yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["app"] ?? null), "request", array()), "url", array())))), "html", null, true);
        echo "\">KZ</a>
                            </div>

                            ";
        // line 27
        if (call_user_func_array($this->env->getFunction('staticCall')->getCallable(), array("common/models/Instructions", "getInstruction", array(0 => "main")))) {
            // line 30
            echo "                                <div class=\"col-auto align-self-center ml-auto\">
                                    <a style=\"margin-right:-1rem; padding:0.4rem 1.2rem; font-size:1.6rem;\" class=\"bg-warning d-inline-block text-white\" href=\"";
            // line 31
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('organizationPath')->getCallable(), array(array(0 => "/main/download", "instruction" => "main"))), "html", null, true);
            echo "\"><i class=\"fa fa-question-circle\"></i></a>
                                </div>
                            ";
        }
        // line 34
        echo "
                            <div class=\"col-12\"></div>

                            <div class=\"col align-self-end mb-2\">
                                <h5 class=\"text-white\">
                                    ";
        // line 39
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Добро пожаловать в Систему Дистанционного Обучения и Тестирования")), "html", null, true);
        echo "
                                </h5>
                            </div>

                        </div>
                    </div>
                </div>

                <div class=\"form-group mt-3\" attribute=\"login\">
                    <div class=\"input-group\">
                        <input autocomplete=\"off\" type=\"text\" class=\"form-control\" name=\"login\" placeholder=\"Email\" />
                    </div>
                </div>

                <div class=\"form-group \" attribute=\"password\">
                    <div class=\"input-group\">
                        <input autocomplete=\"off\" type=\"password\" class=\"form-control\" name=\"password\" placeholder=\"";
        // line 55
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Пароль")), "html", null, true);
        echo "\" />
                    </div>
                </div>

                <div class=\"row align-content-center mb-2\">
                    <div class=\"col\">
                        <div class=\"custom-control custom-checkbox form-group mb-2\" attribute=\"remember_me\">
                            <input id=\"remember_me\" name=\"remember_me\" type=\"checkbox\" class=\"custom-control-input\">
                            <label class=\"custom-control-label\" for=\"remember_me\">";
        // line 63
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Запомнить")), "html", null, true);
        echo "</label>
                        </div>
                    </div>

                    <div class=\"col text-right\">
                        <a href=\"";
        // line 68
        echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path(array(0 => "auth/restore")), "html", null, true);
        echo "\" target=\"_full\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Забыли пароль?")), "html", null, true);
        echo "</a>
                    </div>
                </div>

                <div class=\"form-group mb-1\" style=\"\">
                    <button style=\"width:100%; cursor:pointer;\" type=\"submit\" class=\"btn btn-success text-center\">";
        // line 73
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Войти")), "html", null, true);
        echo "</button>
                </div>
                <div class=\"form-group\">
                    <a href=\"";
        // line 76
        echo twig_escape_filter($this->env, $this->env->getExtension('yii\twig\Extension')->path(array(0 => "auth/registration")), "html", null, true);
        echo "\" style=\"width:100%; cursor:pointer;\" class=\"btn btn-info text-center\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Регистрация")), "html", null, true);
        echo "</a>
                </div>
                ";
        // line 78
        $this->env->getExtension('yii\twig\Extension')->endWidget("e_form");
        echo "

                ";
        // line 80
        if ((twig_constant("YII_DEBUG") == true)) {
            // line 81
            echo "                    <div class=\"alert-danger p-3 mt-1\">WARNING: Debug is ON</div>
                ";
        }
        // line 83
        echo "            </div>
        </div>
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "login.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  152 => 83,  148 => 81,  146 => 80,  141 => 78,  134 => 76,  128 => 73,  118 => 68,  110 => 63,  99 => 55,  80 => 39,  73 => 34,  67 => 31,  64 => 30,  62 => 27,  54 => 24,  47 => 22,  39 => 16,  37 => 9,  33 => 8,  27 => 4,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{{ void(this.setTitle(translate('main','Логин'))) }}
{#{% set bundle = register_asset_bundle('yii/captcha/CaptchaAsset', true) %}#}
{#{{ bundle.sourcePath }}#}
<div class=\"action-content h-100\">
    <div class=\"row justify-content-center h-100\">
        <div class=\"align-self-center\" style=\"width:370px; margin-top:-60px;\">
            <div class=\"white-block\">
                {{ use('app/widgets/EForm/EForm') }}
                {% set form = e_form_begin({
                    'htmlOptions' : {
                        'id' : 'loginForm',
                        'method' : 'post',
                        'action' : path(['/auth/login'])
                    }
                }) %}

                <div class=\"auth-background-block background-block bg-info border-warning\" style=\"height:170px; border-top:5px solid; margin:-20px -20px 0 -20px;\">
                    <div class=\"block-bg\"></div>
                    <div class=\"inner h-100 px-3\">
                        <div class=\"row h-100\" style=\"margin-top:-6px;\">
                            <div class=\"col-auto align-self-center text-white\">
                                <a class=\"{{ app.language == 'ru-RU' ? 'text-white' : '' }}\" target=\"_full\" href=\"{{ organizationPath({0: '/auth/language', ln : \"ru-RU\", return: app.request.url}) }}\">RU</a>
                                |
                                <a class=\"{{ app.language == 'ru-RU' ? 'text-white' : '' }}\" target=\"_full\" href=\"{{ organizationPath({0: '/auth/language', ln : \"kk-KZ\", return: app.request.url}) }}\">KZ</a>
                            </div>

                            {% if staticCall(\"common/models/Instructions\", \"getInstruction\", [
                            'main'
                            ]) %}
                                <div class=\"col-auto align-self-center ml-auto\">
                                    <a style=\"margin-right:-1rem; padding:0.4rem 1.2rem; font-size:1.6rem;\" class=\"bg-warning d-inline-block text-white\" href=\"{{ organizationPath({0: '/main/download', 'instruction' : 'main'}) }}\"><i class=\"fa fa-question-circle\"></i></a>
                                </div>
                            {% endif %}

                            <div class=\"col-12\"></div>

                            <div class=\"col align-self-end mb-2\">
                                <h5 class=\"text-white\">
                                    {{ translate('main','Добро пожаловать в Систему Дистанционного Обучения и Тестирования') }}
                                </h5>
                            </div>

                        </div>
                    </div>
                </div>

                <div class=\"form-group mt-3\" attribute=\"login\">
                    <div class=\"input-group\">
                        <input autocomplete=\"off\" type=\"text\" class=\"form-control\" name=\"login\" placeholder=\"Email\" />
                    </div>
                </div>

                <div class=\"form-group \" attribute=\"password\">
                    <div class=\"input-group\">
                        <input autocomplete=\"off\" type=\"password\" class=\"form-control\" name=\"password\" placeholder=\"{{ translate('main','Пароль') }}\" />
                    </div>
                </div>

                <div class=\"row align-content-center mb-2\">
                    <div class=\"col\">
                        <div class=\"custom-control custom-checkbox form-group mb-2\" attribute=\"remember_me\">
                            <input id=\"remember_me\" name=\"remember_me\" type=\"checkbox\" class=\"custom-control-input\">
                            <label class=\"custom-control-label\" for=\"remember_me\">{{ translate('main','Запомнить') }}</label>
                        </div>
                    </div>

                    <div class=\"col text-right\">
                        <a href=\"{{ path(['auth/restore']) }}\" target=\"_full\">{{ translate('main','Забыли пароль?') }}</a>
                    </div>
                </div>

                <div class=\"form-group mb-1\" style=\"\">
                    <button style=\"width:100%; cursor:pointer;\" type=\"submit\" class=\"btn btn-success text-center\">{{ translate('main','Войти') }}</button>
                </div>
                <div class=\"form-group\">
                    <a href=\"{{ path(['auth/registration']) }}\" style=\"width:100%; cursor:pointer;\" class=\"btn btn-info text-center\">{{ translate('main','Регистрация') }}</a>
                </div>
                {{ e_form_end() }}

                {% if constant('YII_DEBUG') == true %}
                    <div class=\"alert-danger p-3 mt-1\">WARNING: Debug is ON</div>
                {% endif %}
            </div>
        </div>
    </div>
</div>", "login.twig", "E:\\dists\\openServer\\OpenServer\\domains\\krw\\protected\\web\\views\\auth\\login.twig");
    }
}
