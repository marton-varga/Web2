using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using MovieWatcher.Models;
using System;
using System.Collections.Generic;
using System.IO;
using System.Xml.Serialization;

namespace MovieWatcher.Controllers
{
    public class MoviesController : Controller
    {
        public IActionResult MyMovies(Searcher searcher)
        {
            MyMoviesViewModel myMoviesViewModel = new MyMoviesViewModel();
            User user = UsersService.GetUserByEmail(HttpContext.Session.GetString("uname"));
            myMoviesViewModel.searcher = new Searcher();
            if (searcher.keyWords != null)
            {
                myMoviesViewModel.filmWrapper = MoviesService.SearcherResult(HttpContext.Session.GetString("uname"), searcher.keyWords, searcher.filter);
            }
            else
            {
                myMoviesViewModel.filmWrapper = MoviesService.FilmViewListing(user);
            }
            return View(myMoviesViewModel);
        }


        [HttpPost]
        public IActionResult ModifyFilmRequest(int filmId)
        {
            Database db = new Database();
            Film film = db.Films.Find(filmId);
            AddFilmWrapper afw = MoviesService.FilmToAddFilmWrapper(film);
            return View(afw);
        }
        public IActionResult ModifyFilmPost(AddFilmWrapper afw)
        {
            afw.UserId = UsersService.GetUserByEmail(HttpContext.Session.GetString("uname")).Id;
            afw.Actors = new List<string>();
            
            foreach (var item in HttpContext.Request.Form)
            {
                if (item.Key.Contains("actor") && item.Value != "")
                {
                    afw.Actors.Add(item.Value);
                }
            }
            if (MoviesService.ModifyFilm(afw))
            {
                return View();
            }
            else
            {
                return View("ModifyMovie", afw);
            }
        }




        [HttpPost]
        public IActionResult DeleteFilmRequest(int filmId)
        {
            Database db = new Database();
            Film film = db.Films.Find(filmId);
            FilmViewModel fvm = MoviesService.GetFilmView(film);
            return View(fvm);
        }
        [HttpPost]
        public IActionResult DeleteFilm(int filmId)
        {
            Database db = new Database();
            MoviesService.DeleteFilmLeftBehinds(db.Films.Find(filmId));
            db.Films.Remove(db.Films.Find(filmId));
            db.SaveChanges();
            return View();
        }
        public IActionResult AddMovie()
        {
            return View();
        }
        [HttpPost]
        public IActionResult AddMoviePost(AddFilmWrapper afw)
        {
            afw.Added = DateTime.Now;
            afw.IsWatched = true;
            if(afw.Actors == null)
            {
                afw.Actors = new List<string>();
            }
            foreach (var item in HttpContext.Request.Form)
            {
                if (item.Key.Contains("actor"))
                {
                    afw.Actors.Add(item.Value);

                }
            }
            //TODO: Forgot to check whether this line is needed
            afw.UserId = UsersService.GetUserByEmail(HttpContext.Session.GetString("uname")).Id;
            if (MoviesService.AddFilm(afw))
            {
                return View();
            }
            else
            {
                return View("AddMovie");
            }
        }

        
        
        
        
        
        
        
        
        [HttpPost]
        public IActionResult DownloadXML(MyMoviesViewModel mmvm)
        {
            Database db = new Database();
            List<XmlHelper> XmlList = new List<XmlHelper>();
            foreach (var f in db.Films)
            {
                if(f.UserId == UsersService.GetUserByEmail(HttpContext.Session.GetString("uname")).Id)
                {
                    XmlList.Add(new XmlHelper(f.Title, f.Added));
                }
            }
            string path = ".\\DownloadXml\\xml.xml";
            if (!System.IO.File.Exists(path))
            {
                System.IO.File.Create(path).Close();
            }
                using (StreamWriter sw = new StreamWriter(path))
            {
                XmlSerializer ser = new XmlSerializer(XmlList.GetType());
                ser.Serialize(sw, XmlList);
            }
            FileStream fs = new FileStream(path, FileMode.Open);
            return File(fs, "text/xml", path);
        }

        public IActionResult WatchList()
        {
            return View();
        }
    }
}
