<?php

/* form.twig */
class __TwigTemplate_c3f616d1e6127e6780be848875955e93a19b1551858461840231d5543f18e5c5 extends Twig_Template
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
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('void')->getCallable(), array(yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["this"] ?? null), "setTitle", array(0 => call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Карточка предприятия"))), "method"))), "html", null, true);
        echo "
<div class=\"action-content\">

    ";
        // line 4
        $this->env->getExtension('yii\twig\Extension')->addUses("app/widgets/EForm/EForm");
        echo "
    ";
        // line 5
        $context["form"] = $this->env->getExtension('yii\twig\Extension')->beginWidget("e_form", array("htmlOptions" => array("id" => "newOrganizationsForm", "method" => "post", "action" => $this->env->getExtension('yii\twig\Extension')->path(array(0 => "/admin/organizations/add")))));
        // line 12
        echo "
    ";
        // line 13
        $this->env->getExtension('yii\twig\Extension')->addUses("app/widgets/EUploader/EUploader");
        echo "
    ";
        // line 14
        echo $this->env->getExtension('yii\twig\Extension')->widget("e_uploader", array("standalone" => true));
        echo "
    ";
        // line 15
        $this->env->getExtension('yii\twig\Extension')->addUses("app/widgets/ECropper/ECropper");
        echo "
    ";
        // line 16
        echo $this->env->getExtension('yii\twig\Extension')->widget("e_cropper");
        echo "

    <script type=\"text/template\" id=\"file_template\">
        <div class=\"attached-file\" style=\"margin-bottom:15px;\">
            <input type=\"hidden\" value='<%=JSON.stringify(data.model.toJSON())%>' name=\"uploaded[]\"/>
            <a target='_blank' href=\"<%=data.model.get(\"url\")%>\"><%=data.model.get(\"name\")%></a>
            <a style=\"margin-left:15px; cursor:pointer;\" class=\"delete-file btn-link\">&times;</a>
        </div>
    </script>

    <script id=\"attached_file_template\" type=\"text/template\">
        <div class='uploaded-file'>
            <% if (!data.error && data.percent>0 && data.percent < 100) { %>
            <div style='margin-top:5px; margin-bottom:5px;' class=\"progress progress-striped\">
                <div class=\"progress-bar progress-bar-info\" role=\"progressbar\" aria-valuenow=\"20\" aria-valuemin=\"0\"
                     aria-valuemax=\"100\" style=\"width: <%=data.percent%>%\">
                    <span><%=(Math.ceil(data.loaded/1024)) + \"kb\" %>/<%=(Math.ceil(data.total/1024)) + \"kb\"%></span>
                </div>
            </div>
            <% } %>
        </div>
    </script>

    <div class=\"asm-wrapper\">
        <div class=\"asm-block\" style=\"width: 100%;\">
            <div class=\"cont\">
                <h4 style=\"margin-bottom:30px;\">";
        // line 42
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Регистрация Организации")), "html", null, true);
        echo "</h4>

                <div class=\"row\">
                    <div class=\"col-md-3\">
                        <h5 style=\"margin-bottom:30px;\">";
        // line 46
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Лого организации")), "html", null, true);
        echo "</h5>
                        <div class=\"uploader pull-left\">
                            <input type=\"hidden\" name=\"logo\" id=\"logo\" value=\"";
        // line 48
        echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["model"] ?? null), "logo", array()), "html", null, true);
        echo "\"/>
                            <div class=\"uploaded-photo\">
                                ";
        // line 50
        if ((yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["model"] ?? null), "logo", array()) != "")) {
            // line 51
            echo "                                    <img class=\"img-thumbnail\" style=\"max-width:150px;\"
                                         alt=\"";
            // line 52
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Лого организации")), "html", null, true);
            echo "\" src=\"";
            echo twig_escape_filter($this->env, yii\twig\Template::attribute($this->env, $this->getSourceContext(), yii\twig\Template::attribute($this->env, $this->getSourceContext(), ($context["model"] ?? null), "logoUrl", array()), "url", array()), "html", null, true);
            echo "\">
                                ";
        }
        // line 54
        echo "                            </div>
                            <h4 style=\"margin-top: 10px\"><a style=\"color:#fff; cursor:pointer;\" class=\"btn btn-info upload-button\">
                                    <li class=\"fa fa-file\"></li> ";
        // line 56
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Загрузить")), "html", null, true);
        echo "</a></h4>
                            <input style=\"display:none\" type=\"file\" name=\"file\"/>
                        </div>
                    </div>

                    <div class=\"col-md-9\">
                        <div class=\"row\">
                            <div class=\"col-md-12\">
                                <div class=\"form-group\" attribute=\"name\">
                                    <input class=\"form-control\" type=\"text\" name=\"name\" id=\"name\"
                                           placeholder=\"";
        // line 66
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Название организации")), "html", null, true);
        echo "\"/>
                                </div>
                            </div>
                        </div>

                        <div class=\"row\">
                            <div class=\"col-sm-6\">
                                <div class=\"form-group\" attribute=\"login\">
                                    <input placeholder=\"";
        // line 74
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Логин администратора")), "html", null, true);
        echo "\" class=\"form-control\" type=\"text\" name=\"login\" id=\"login\" />
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-group\" attribute=\"password\">
                                    <input placeholder=\"";
        // line 79
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Пароль администратора")), "html", null, true);
        echo "\" class=\"form-control\" type=\"password\" name=\"password\" id=\"password\" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class=\"form-group text-center\" style=\"margin-bottom:0; margin-top:30px;\">
                    <button type=\"submit\"
                            class=\"btn btn-success\">";
        // line 89
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('translate')->getCallable(), array("main", "Сохранить организацию")), "html", null, true);
        echo "</button>
                    <div class=\"clearfix\"></div>
                </div>

            </div>
        </div>
    </div>

    ";
        // line 97
        $this->env->getExtension('yii\twig\Extension')->endWidget("e_form");
        echo "
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
        return array (  164 => 97,  153 => 89,  140 => 79,  132 => 74,  121 => 66,  108 => 56,  104 => 54,  97 => 52,  94 => 51,  92 => 50,  87 => 48,  82 => 46,  75 => 42,  46 => 16,  42 => 15,  38 => 14,  34 => 13,  31 => 12,  29 => 5,  25 => 4,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{{ void(this.setTitle(translate('main','Карточка предприятия'))) }}
<div class=\"action-content\">

    {{ use('app/widgets/EForm/EForm') }}
    {% set form = e_form_begin({
        'htmlOptions' : {
            'id' : 'newOrganizationsForm',
            'method' : 'post',
            'action' : path({0:'/admin/organizations/add'})
        }
    }) %}

    {{ use('app/widgets/EUploader/EUploader') }}
    {{ e_uploader_widget({'standalone': true}) }}
    {{ use('app/widgets/ECropper/ECropper') }}
    {{ e_cropper_widget() }}

    <script type=\"text/template\" id=\"file_template\">
        <div class=\"attached-file\" style=\"margin-bottom:15px;\">
            <input type=\"hidden\" value='<%=JSON.stringify(data.model.toJSON())%>' name=\"uploaded[]\"/>
            <a target='_blank' href=\"<%=data.model.get(\"url\")%>\"><%=data.model.get(\"name\")%></a>
            <a style=\"margin-left:15px; cursor:pointer;\" class=\"delete-file btn-link\">&times;</a>
        </div>
    </script>

    <script id=\"attached_file_template\" type=\"text/template\">
        <div class='uploaded-file'>
            <% if (!data.error && data.percent>0 && data.percent < 100) { %>
            <div style='margin-top:5px; margin-bottom:5px;' class=\"progress progress-striped\">
                <div class=\"progress-bar progress-bar-info\" role=\"progressbar\" aria-valuenow=\"20\" aria-valuemin=\"0\"
                     aria-valuemax=\"100\" style=\"width: <%=data.percent%>%\">
                    <span><%=(Math.ceil(data.loaded/1024)) + \"kb\" %>/<%=(Math.ceil(data.total/1024)) + \"kb\"%></span>
                </div>
            </div>
            <% } %>
        </div>
    </script>

    <div class=\"asm-wrapper\">
        <div class=\"asm-block\" style=\"width: 100%;\">
            <div class=\"cont\">
                <h4 style=\"margin-bottom:30px;\">{{ translate('main','Регистрация Организации') }}</h4>

                <div class=\"row\">
                    <div class=\"col-md-3\">
                        <h5 style=\"margin-bottom:30px;\">{{ translate('main','Лого организации') }}</h5>
                        <div class=\"uploader pull-left\">
                            <input type=\"hidden\" name=\"logo\" id=\"logo\" value=\"{{ model.logo }}\"/>
                            <div class=\"uploaded-photo\">
                                {% if (model.logo!= \"\") %}
                                    <img class=\"img-thumbnail\" style=\"max-width:150px;\"
                                         alt=\"{{ translate('main', 'Лого организации') }}\" src=\"{{ model.logoUrl.url }}\">
                                {% endif %}
                            </div>
                            <h4 style=\"margin-top: 10px\"><a style=\"color:#fff; cursor:pointer;\" class=\"btn btn-info upload-button\">
                                    <li class=\"fa fa-file\"></li> {{ translate(\"main\",\"Загрузить\") }}</a></h4>
                            <input style=\"display:none\" type=\"file\" name=\"file\"/>
                        </div>
                    </div>

                    <div class=\"col-md-9\">
                        <div class=\"row\">
                            <div class=\"col-md-12\">
                                <div class=\"form-group\" attribute=\"name\">
                                    <input class=\"form-control\" type=\"text\" name=\"name\" id=\"name\"
                                           placeholder=\"{{ translate('main','Название организации') }}\"/>
                                </div>
                            </div>
                        </div>

                        <div class=\"row\">
                            <div class=\"col-sm-6\">
                                <div class=\"form-group\" attribute=\"login\">
                                    <input placeholder=\"{{ translate('main','Логин администратора') }}\" class=\"form-control\" type=\"text\" name=\"login\" id=\"login\" />
                                </div>
                            </div>
                            <div class=\"col-sm-6\">
                                <div class=\"form-group\" attribute=\"password\">
                                    <input placeholder=\"{{ translate('main','Пароль администратора') }}\" class=\"form-control\" type=\"password\" name=\"password\" id=\"password\" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class=\"form-group text-center\" style=\"margin-bottom:0; margin-top:30px;\">
                    <button type=\"submit\"
                            class=\"btn btn-success\">{{ translate('main','Сохранить организацию') }}</button>
                    <div class=\"clearfix\"></div>
                </div>

            </div>
        </div>
    </div>

    {{ e_form_end() }}
</div>", "form.twig", "E:\\dists\\openServer\\OpenServer\\domains\\krw\\protected\\web\\modules\\admin\\views\\organizations\\form.twig");
    }
}
