#pragma checksum "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml" "{ff1816ec-aa5e-4d10-87f7-6f4963833460}" "0da11df8fb72cf7ec690ce54a454450b8f5b1550"
// <auto-generated/>
#pragma warning disable 1591
[assembly: global::Microsoft.AspNetCore.Razor.Hosting.RazorCompiledItemAttribute(typeof(AspNetCore.Views_Movies_AddMovie), @"mvc.1.0.view", @"/Views/Movies/AddMovie.cshtml")]
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
    [global::Microsoft.AspNetCore.Razor.Hosting.RazorSourceChecksumAttribute(@"SHA1", @"0da11df8fb72cf7ec690ce54a454450b8f5b1550", @"/Views/Movies/AddMovie.cshtml")]
    [global::Microsoft.AspNetCore.Razor.Hosting.RazorSourceChecksumAttribute(@"SHA1", @"f492a852f8c36f23bf44af527f62223dcf210ba1", @"/Views/_ViewImports.cshtml")]
    #nullable restore
    public class Views_Movies_AddMovie : global::Microsoft.AspNetCore.Mvc.Razor.RazorPage<AddFilmWrapper>
    #nullable disable
    {
        #pragma warning disable 1998
        public async override global::System.Threading.Tasks.Task ExecuteAsync()
        {
#nullable restore
#line 2 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
  
    ViewData["Title"] = "Új Film Hozzáadása";

#line default
#line hidden
#nullable disable
            WriteLiteral("<div class=\"insetContent\">\r\n");
#nullable restore
#line 6 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
 using (Html.BeginForm("AddMoviePost", "Movies", FormMethod.Post))
{
    

#line default
#line hidden
#nullable disable
#nullable restore
#line 8 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
Write(Html.LabelFor(f => f.Title));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 10 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
Write(Html.TextBoxFor(f => f.Title, new { required = "required" }));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 12 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
Write(Html.LabelFor(f => f.Year));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 14 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
Write(Html.TextBoxFor(f => f.Year, new { type = "number", min = 1, required = "required" }));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 16 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
Write(Html.LabelFor(f => f.Style));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 18 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
Write(Html.TextBoxFor(f => f.Style, new { required = "required" }));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 20 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
Write(Html.LabelFor(f => f.Director));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 22 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
Write(Html.TextBoxFor(f => f.Director, new { required = "required" }));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
            WriteLiteral(@"    <p>Színészek</p>
    <div id=""addActors"">
        <input type=""text"" name=""actor_0"" id=""actor_0"" class=""actorInput"" /><button id=""removeActor_0"" class=""removeActor"">Törlés</button>
        <br />
    </div>
    <br />
    <button id=""addActorButton"">Új színész hozzáadása</button>
    <br />
");
#nullable restore
#line 34 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
Write(Html.LabelFor(f => f.Watched));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n");
#nullable restore
#line 36 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
Write(Html.TextBoxFor(f => f.Watched, new { type = "date" }));

#line default
#line hidden
#nullable disable
            WriteLiteral("    <br />\r\n    <br />\r\n    <input type=\"submit\" value=\"Film hozzáadása\" />\r\n");
#nullable restore
#line 40 "C:\Users\Zbook\Desktop\Cs\MovieWatcher\MovieWatcher\Views\Movies\AddMovie.cshtml"
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

    document.getElementsByClassName(""removeActor"")[0].addEventListener(""click"", removeActor, false);
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