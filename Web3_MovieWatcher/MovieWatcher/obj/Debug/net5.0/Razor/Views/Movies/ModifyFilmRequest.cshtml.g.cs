#pragma checksum "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml" "{ff1816ec-aa5e-4d10-87f7-6f4963833460}" "49b16425ef49055f95bb08af98b7f29a8f485498"
// <auto-generated/>
#pragma warning disable 1591
[assembly: global::Microsoft.AspNetCore.Razor.Hosting.RazorCompiledItemAttribute(typeof(AspNetCore.Views_Movies_ModifyFilmRequest), @"mvc.1.0.view", @"/Views/Movies/ModifyFilmRequest.cshtml")]
namespace AspNetCore
{
    #line hidden
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.AspNetCore.Mvc.Rendering;
    using Microsoft.AspNetCore.Mvc.ViewFeatures;
#nullable restore
#line 1 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\_ViewImports.cshtml"
using MovieWatcher;

#line default
#line hidden
#nullable disable
#nullable restore
#line 2 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\_ViewImports.cshtml"
using MovieWatcher.Models;

#line default
#line hidden
#nullable disable
    [global::Microsoft.AspNetCore.Razor.Hosting.RazorSourceChecksumAttribute(@"SHA1", @"49b16425ef49055f95bb08af98b7f29a8f485498", @"/Views/Movies/ModifyFilmRequest.cshtml")]
    [global::Microsoft.AspNetCore.Razor.Hosting.RazorSourceChecksumAttribute(@"SHA1", @"f492a852f8c36f23bf44af527f62223dcf210ba1", @"/Views/_ViewImports.cshtml")]
    #nullable restore
    public class Views_Movies_ModifyFilmRequest : global::Microsoft.AspNetCore.Mvc.Razor.RazorPage<AddFilmWrapper>
    #nullable disable
    {
        #pragma warning disable 1998
        public async override global::System.Threading.Tasks.Task ExecuteAsync()
        {
#nullable restore
#line 2 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
  
    ViewData["Title"] = "Film Módosítása";

#line default
#line hidden
#nullable disable
            WriteLiteral("<div class=\"insetContent\">\r\n");
#nullable restore
#line 6 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
 using (Html.BeginForm("ModifyFilmPost", "Movies", FormMethod.Post))
{
    

#line default
#line hidden
#nullable disable
#nullable restore
#line 8 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.LabelFor(f => f.Title));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 10 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.TextBoxFor(f => f.Title, new { value = Model.Title, required = "required" }));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 12 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.LabelFor(f => f.Year));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 14 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.TextBoxFor(f => f.Year, new { value = Model.Year, type = "number", min = 1, required = "required" }));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 16 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.LabelFor(f => f.Style));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 18 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.TextBoxFor(f => f.Style, new { value = Model.Style, required = "required" }));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 20 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.LabelFor(f => f.Director));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 22 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.TextBoxFor(f => f.Director, new { value = Model.Director, required = "required" }));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n    <p>Színészek</p>\r\n");
            WriteLiteral("    <div id=\"addActors\">\r\n\r\n");
#nullable restore
#line 28 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
          int i = 0;
            

#line default
#line hidden
#nullable disable
#nullable restore
#line 29 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
             foreach (var a in Model.Actors)
            {

#line default
#line hidden
#nullable disable
            WriteLiteral("                <input type=\"text\"");
            BeginWriteAttribute("value", " value=\"", 960, "\"", 970, 1);
#nullable restore
#line 31 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
WriteAttributeValue("", 968, a, 968, 2, false);

#line default
#line hidden
#nullable disable
            EndWriteAttribute();
            BeginWriteAttribute("name", " name=\"", 971, "\"", 986, 2);
            WriteAttributeValue("", 978, "actor_", 978, 6, true);
#nullable restore
#line 31 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
WriteAttributeValue("", 984, i, 984, 2, false);

#line default
#line hidden
#nullable disable
            EndWriteAttribute();
            BeginWriteAttribute("id", " id=\"", 987, "\"", 1000, 2);
            WriteAttributeValue("", 992, "actor_", 992, 6, true);
#nullable restore
#line 31 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
WriteAttributeValue("", 998, i, 998, 2, false);

#line default
#line hidden
#nullable disable
            EndWriteAttribute();
            WriteLiteral(" class=\"actorInput\" /><button");
            BeginWriteAttribute("id", " id=\"", 1030, "\"", 1049, 2);
            WriteAttributeValue("", 1035, "removeActor_", 1035, 12, true);
#nullable restore
#line 31 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
WriteAttributeValue("", 1047, i, 1047, 2, false);

#line default
#line hidden
#nullable disable
            EndWriteAttribute();
            WriteLiteral(" class=\"removeActor\">Törlés</button>\r\n                <br /><br />\r\n");
#nullable restore
#line 33 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
                i = i + 1;   
            }

#line default
#line hidden
#nullable disable
            WriteLiteral("\r\n\r\n    </div>\r\n    <br />\r\n    <button id=\"addActorButton\">Új színész hozzáadása</button>\r\n    <br />\r\n");
#nullable restore
#line 43 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.LabelFor(f => f.Watched));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 45 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.TextBoxFor(f => f.Watched, new { @Value = Model.Watched, type = "datetime" }));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 47 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.HiddenFor(f => f.Id, new { value = Model.Id }));

#line default
#line hidden
#nullable disable
#nullable restore
#line 48 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
Write(Html.HiddenFor(f => f.UserId, new { value = Model.UserId }));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n    <input type=\"submit\" value=\"Film hozzáadása\" />\r\n");
#nullable restore
#line 51 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\ModifyFilmRequest.cshtml"
}

#line default
#line hidden
#nullable disable
            WriteLiteral(@"    </div>

<script>
    var actors;
    function removeActor(e) {
        e.preventDefault();
        actors = [];
        let len = document.getElementsByClassName(""actorInput"").length;
        for (let i = 0; i < len; i++) {
            if (document.getElementsByClassName(""actorInput"")[i].value != """") {
                actors.push(document.getElementsByClassName(""actorInput"")[i].value);
            }
        }
        actors.splice(e.target.id.split(""_"")[1], 1);
        for (let i = 0; i < document.getElementsByClassName(""removeActor"").length; i++) {
            document.getElementsByClassName(""removeActor"")[i].removeEventListener(""click"", removeActor, false);
        }
        len = actors.length;
        document.getElementById(""addActors"").innerHTML = """";
        for (let i = 0; i < len; i++) {
            document.getElementById(""addActors"").innerHTML += ""<input type='text' value='"" + actors[i] + ""' name='actor_"" + i + ""' id='actor_"" + i + ""' class='actorInput'/>"";
            docu");
            WriteLiteral(@"ment.getElementById(""addActors"").innerHTML += ""<button id='removeActor_"" + i + ""' class='removeActor'>Törlés</button><br /><br />"";
        }
        document.getElementById(""addActors"").innerHTML += ""<input type='text' name='actor_"" + len + ""' id='actor_"" + len + ""' class='actorInput'/>"";
        document.getElementById(""addActors"").innerHTML += ""<button id='removeActor_"" + len + ""' class='removeActor'>Törlés</button><br />"";
        for (let i = 0; i < document.getElementsByClassName(""removeActor"").length; i++) {
            document.getElementsByClassName(""removeActor"")[i].addEventListener(""click"", removeActor, false);
        }
    }
    function addActorButtonClick(e) {
        e.preventDefault();
        actors = [];
        let len = document.getElementsByClassName(""actorInput"").length;
        for (let i = 0; i < document.getElementsByClassName(""removeActor"").length; i++) {
            document.getElementsByClassName(""removeActor"")[i].removeEventListener(""click"", removeActor, false);
   ");
            WriteLiteral(@"     }
        for (let i = 0; i < len; i++) {
            if (document.getElementsByClassName(""actorInput"")[i].value != '' && document.getElementsByClassName(""actorInput"")[i].value != undefined) {
                actors.push(document.getElementsByClassName(""actorInput"")[i].value);
            }
        }
        console.log(actors);
        len = actors.length;
        document.getElementById(""addActors"").innerHTML = """";
        for (let i = 0; i < len; i++) {
            document.getElementById(""addActors"").innerHTML += ""<input type='text' value='"" + actors[i] + ""' name='actor_"" + i + ""' id='actor_"" + i + ""' class='actorInput'/>"";
            document.getElementById(""addActors"").innerHTML += ""<button id='removeActor_"" + i + ""' class='removeActor'>Törlés</button><br /><br />"";
        }
        document.getElementById(""addActors"").innerHTML += ""<input type='text' name='actor_"" + len + ""' id='actor_"" + len + ""' class='actorInput'/>"";
        document.getElementById(""addActors"").innerHTML += ""<bu");
            WriteLiteral(@"tton id='removeActor_"" + len + ""' class='removeActor'>Törlés</button><br />"";
        for (let i = 0; i < document.getElementsByClassName(""removeActor"").length; i++) {
            document.getElementsByClassName(""removeActor"")[i].addEventListener(""click"", removeActor, false);
        }
    }
    for (let i = 0; i < document.getElementsByClassName(""removeActor"").length; i++){
        console.log(i);
        document.getElementsByClassName(""removeActor"")[i].addEventListener(""click"", removeActor, false);
    }
    document.getElementById(""addActorButton"").addEventListener('click', addActorButtonClick, false);
</script>");
        }
        #pragma warning restore 1998
        #nullable restore
        [global::Microsoft.AspNetCore.Mvc.Razor.Internal.RazorInjectAttribute]
        public global::Microsoft.AspNetCore.Mvc.ViewFeatures.IModelExpressionProvider ModelExpressionProvider { get; private set; } = default!;
        #nullable disable
        #nullable restore
        [global::Microsoft.AspNetCore.Mvc.Razor.Internal.RazorInjectAttribute]
        public global::Microsoft.AspNetCore.Mvc.IUrlHelper Url { get; private set; } = default!;
        #nullable disable
        #nullable restore
        [global::Microsoft.AspNetCore.Mvc.Razor.Internal.RazorInjectAttribute]
        public global::Microsoft.AspNetCore.Mvc.IViewComponentHelper Component { get; private set; } = default!;
        #nullable disable
        #nullable restore
        [global::Microsoft.AspNetCore.Mvc.Razor.Internal.RazorInjectAttribute]
        public global::Microsoft.AspNetCore.Mvc.Rendering.IJsonHelper Json { get; private set; } = default!;
        #nullable disable
        #nullable restore
        [global::Microsoft.AspNetCore.Mvc.Razor.Internal.RazorInjectAttribute]
        public global::Microsoft.AspNetCore.Mvc.Rendering.IHtmlHelper<AddFilmWrapper> Html { get; private set; } = default!;
        #nullable disable
    }
}
#pragma warning restore 1591
