<?php

/* registration.twig */
class __TwigTemplate_00c47af62779f1ffc3df15d30759f60140034bce0d09602fc01389e575efa138 extends Twig_Template
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
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('void')->getCallable(), array(yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "setTitle", array(0 => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Регистрация"))), "method"))), "html", null, true);
        echo "
<div class=\"action-content mb-5\">
    <div class=\"auth-form\">
        <div class=\"row justify-content-center\">
            <div class=\"align-self-center\" style=\"width:370px;\">
                <div class=\"white-block\">

                    ";
        // line 8
        $this->env->getExtension('yii\twig\Extension')->addUses("app/widgets/EForm/EForm");
        echo "
                    ";
        // line 9
        $context["form"] = $this->env->getExtension('yii\twig\Extension')->beginWidget("e_form", array("htmlOptions" => array("id" => "registrationForm", "method" => "post", "action" => $this->env->getExtension('yii\twig\Extension')->path(array(0 => "/auth/registration")))));
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
            echo "                                    <div class=\"col-auto align-self-center ml-auto\">
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

                    <div class=\"mt-3\">

                        <div class=\"page-header mb-3\">
                            <h5>";
        // line 50
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Идентификационный номер/Логин")), "html", null, true);
        echo "</h5>
                        </div>

                        <div class=\"form-group mb-2\" attribute=\"login\">
                            <input type=\"text\" class=\"form-control\" name=\"login\" placeholder=\"";
        // line 54
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Идентификационный номер/Логин")), "html", null, true);
        echo "\" />
                        </div>
                        <div class=\"alert alert-info\">
                            <p class=\"lh-1\">";
        // line 57
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Введите в данное поле идентификационный номер, который вам выдали, либо логин, под которым вас добавили в кадровом учете")), "html", null, true);
        echo "</p>
                        </div>

                        <div class=\"page-header mt-3 mb-3\">
                            <h5>";
        // line 61
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Данные регистрации")), "html", null, true);
        echo "</h5>
                        </div>
                        <div class=\"form-group\" attribute=\"email\">
                            <label class=\"control-label\">";
        // line 64
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Ваш адрес электронной почты")), "html", null, true);
        echo "</label>
                            <input autocomplete=\"off\" type=\"text\" class=\"form-control\" name=\"email\" placeholder=\"Email\" />
                        </div>

                        <div class=\"form-group\" attribute=\"phone\">
                            <label class=\"control-label\">";
        // line 69
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Номер мобильного телефона")), "html", null, true);
        echo "</label>
                            <input autocomplete=\"off\" type=\"text\" class=\"form-control\" name=\"phone\" placeholder=\"";
        // line 70
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "+7(xxx)xxxxxxx")), "html", null, true);
        echo "\" />
                        </div>

                        <div class=\"form-group\" attribute=\"password\">
                            <label class=\"control-label\">";
        // line 74
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Укажите пароль")), "html", null, true);
        echo "</label>
                            <input autocomplete=\"off\" type=\"password\" class=\"form-control\" name=\"password\" placeholder=\"";
        // line 75
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Пароль")), "html", null, true);
        echo "\" />
                        </div>

                        <div class=\"form-group\" attribute=\"fio\">
                            <label class=\"control-label\">";
        // line 79
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Ваше ФИО")), "html", null, true);
        echo "</label>
                            <input autocomplete=\"off\" type=\"text\" class=\"form-control\" name=\"fio\" placeholder=\"";
        // line 80
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "ФИО")), "html", null, true);
        echo "\" />
                        </div>

                        <div class=\"form-group mt-3\" style=\"margin-bottom:0;\">
                            <input style=\"width:100%;\" type=\"submit\" class=\"text-center btn btn-success\" value=\"";
        // line 84
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Зарегистрироваться")), "html", null, true);
        echo "\" />
                        </div>
                    </div>

                    ";
        // line 88
        $this->env->getExtension('yii\twig\Extension')->endWidget("e_form");
        echo "
                </div>
            </div>
        </div>
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "registration.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  168 => 88,  161 => 84,  154 => 80,  150 => 79,  143 => 75,  139 => 74,  132 => 70,  128 => 69,  120 => 64,  114 => 61,  107 => 57,  101 => 54,  94 => 50,  80 => 39,  73 => 34,  67 => 31,  64 => 30,  62 => 27,  54 => 24,  47 => 22,  39 => 16,  37 => 9,  33 => 8,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{{ void(this.setTitle(translate('main','Регистрация'))) }}
<div class=\"action-content mb-5\">
    <div class=\"auth-form\">
        <div class=\"row justify-content-center\">
            <div class=\"align-self-center\" style=\"width:370px;\">
                <div class=\"white-block\">

                    {{ use('app/widgets/EForm/EForm') }}
                    {% set form = e_form_begin({
                        'htmlOptions' : {
                            'id' : 'registrationForm',
                            'method' : 'post',
                            'action' : path(['/auth/registration'])
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

                    <div class=\"mt-3\">

                        <div class=\"page-header mb-3\">
                            <h5>{{ translate('main','Идентификационный номер/Логин') }}</h5>
                        </div>

                        <div class=\"form-group mb-2\" attribute=\"login\">
                            <input type=\"text\" class=\"form-control\" name=\"login\" placeholder=\"{{ translate('main','Идентификационный номер/Логин') }}\" />
                        </div>
                        <div class=\"alert alert-info\">
                            <p class=\"lh-1\">{{ translate('main','Введите в данное поле идентификационный номер, который вам выдали, либо логин, под которым вас добавили в кадровом учете') }}</p>
                        </div>

                        <div class=\"page-header mt-3 mb-3\">
                            <h5>{{ translate('main','Данные регистрации') }}</h5>
                        </div>
                        <div class=\"form-group\" attribute=\"email\">
                            <label class=\"control-label\">{{ translate('main', 'Ваш адрес электронной почты') }}</label>
                            <input autocomplete=\"off\" type=\"text\" class=\"form-control\" name=\"email\" placeholder=\"Email\" />
                        </div>

                        <div class=\"form-group\" attribute=\"phone\">
                            <label class=\"control-label\">{{ translate('main', 'Номер мобильного телефона') }}</label>
                            <input autocomplete=\"off\" type=\"text\" class=\"form-control\" name=\"phone\" placeholder=\"{{ translate('main','+7(xxx)xxxxxxx') }}\" />
                        </div>

                        <div class=\"form-group\" attribute=\"password\">
                            <label class=\"control-label\">{{ translate('main', 'Укажите пароль') }}</label>
                            <input autocomplete=\"off\" type=\"password\" class=\"form-control\" name=\"password\" placeholder=\"{{ translate('main','Пароль') }}\" />
                        </div>

                        <div class=\"form-group\" attribute=\"fio\">
                            <label class=\"control-label\">{{ translate('main', 'Ваше ФИО') }}</label>
                            <input autocomplete=\"off\" type=\"text\" class=\"form-control\" name=\"fio\" placeholder=\"{{ translate('main','ФИО') }}\" />
                        </div>

                        <div class=\"form-group mt-3\" style=\"margin-bottom:0;\">
                            <input style=\"width:100%;\" type=\"submit\" class=\"text-center btn btn-success\" value=\"{{ translate('main','Зарегистрироваться') }}\" />
                        </div>
                    </div>

                    {{ e_form_end() }}
                </div>
            </div>
        </div>
    </div>
</div>", "registration.twig", "E:\\dists\\openServer\\OpenServer\\domains\\krw\\protected\\web\\views\\auth\\registration.twig");
    }
}
