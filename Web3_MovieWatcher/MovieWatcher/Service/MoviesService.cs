using Microsoft.AspNetCore.Http;
using System.Collections.Generic;

#nullable disable

namespace MovieWatcher.Models
{
    public static class MoviesService
    {
        public static List<FilmViewModel> FilmViewListing(User user)
        {
            Database db = new Database();
            List<FilmViewModel> films = new List<FilmViewModel>();
            foreach (Film f in db.Films)
            {
                FilmViewModel fw;
                if (f.UserId == user.Id)
                {
                    Database db2 = new Database();
                    fw = new FilmViewModel();
                    fw.Id = f.Id;
                    fw.Title = f.Title;
                    fw.Year = f.Year;
                    //Database db3 = new Database();
                    if (db2.Styles.Find(f.Style) == null)
                    {
                        Style style = new Style();
                        style.Name = fw.Style;
                        db2.Styles.Add(style);
                        db.SaveChanges();
                    }
                    fw.Style = db2.Styles.Find(f.Style).Name;
                    if (db2.Directors.Find(f.DirectorId) == null)
                    {
                        Director director = new Director();
                        director.Name = fw.Style;
                        db2.Directors.Add(director);
                        db.SaveChanges();
                    }
                    fw.Director = db2.Directors.Find(f.DirectorId).Name;
                    fw.Added = f.Added;
                    fw.Watched = f.Watched;
                    fw.IsWatched = f.IsWatched;
                    fw.Actors = new List<string>();
                    //actors
                    foreach (ActorInFilm af in db2.ActorInFilms)
                    {
                        if (f.Id == af.FilmId)
                        {
                            Database db3 = new Database();
                            Actor actor = db3.Actors.Find(af.ActorId);
                            if (!fw.Actors.Contains(actor.Name))
                            {
                                fw.Actors.Add(actor.Name);
                            }
                        }
                    }
                    films.Add(fw);
                }
            }
            return films;
        }
        public static List<FilmViewModel> SearcherResult(string uname, string keyWords, string filter)
        {
            List<FilmViewModel> result = new List<FilmViewModel>();
            List<FilmViewModel> films = FilmViewListing(UsersService.GetUserByEmail(uname));
            foreach (var f in films)
            {
                switch (filter)
                {
                    case "TITLE":
                        if (f.Title.ToLower().Contains(keyWords.ToLower()))
                        {
                            result.Add(f);
                        }
                        break;
                    case "STYLE":
                        if (f.Style.ToLower().Contains(keyWords.ToLower()))
                        {
                            result.Add(f);
                        }
                        break;
                    case "DIRECTOR":
                        if (f.Director.ToLower().Contains(keyWords.ToLower()))
                        {
                            result.Add(f);
                        }
                        break;
                    case "ACTOR":
                        foreach (var a in f.Actors)
                        {
                            if (a.ToLower().Contains(keyWords.ToLower()))
                            {
                                result.Add(f);
                                break;
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
            return result;
        }

        public static FilmViewModel GetFilmView(Film film)
        {
            FilmViewModel fvm = new FilmViewModel();
            Database db = new Database();
            fvm = new FilmViewModel();
            fvm.Id = film.Id;
            fvm.Title = film.Title;
            fvm.Year = film.Year;
            fvm.Style = db.Styles.Find(film.Style).Name;
            fvm.Director = db.Directors.Find(film.DirectorId).Name;
            fvm.Added = film.Added;
            fvm.Watched = film.Watched;
            fvm.IsWatched = film.IsWatched;
            fvm.Actors = new List<string>();
            foreach (var a in db.ActorInFilms)
            {
                if (film.Id == a.FilmId)
                {
                    Database db2 = new Database();
                    Actor actor = db2.Actors.Find(a.ActorId);
                    if (!fvm.Actors.Contains(actor.Name))
                    {
                        fvm.Actors.Add(actor.Name);
                    }
                }
            }
            return fvm;
        }
        
        
        
        public static void DeleteFilmLeftBehinds(Film film)
        {
            Database db = new Database();
            List<ActorInFilm> actorInFilms = new List<ActorInFilm>();
            List<Actor> actors = new List<Actor>();
            foreach(Actor a in db.Actors)
            {
                bool actorHasOtherFilm = false;
                Database db2 = new Database();
                foreach (ActorInFilm af in db2.ActorInFilms)
                {
                    if (af.ActorId == a.Id && af.FilmId == film.Id)
                    {
                        actorInFilms.Add(af);
                    }
                    else if (af.ActorId == a.Id && af.FilmId != film.Id)
                    {
                        actorHasOtherFilm = true;
                    }
                }
                if (!actorHasOtherFilm)
                {
                    actors.Add(a);
                }
            }
            foreach(var af in actorInFilms)
            {
                db.ActorInFilms.Remove(af);
            }
            foreach (var a in actors)
            {
                db.Actors.Remove(a);
            }
            Director director = db.Directors.Find(film.DirectorId);
            bool ofOtherFilm = false;
            foreach (var f in db.Films)
            {
                if (f.Id != film.Id && director.Id == f.DirectorId)
                {
                    ofOtherFilm = true;
                    break;
                }
            }
            if (!ofOtherFilm)
            {
                db.Directors.Remove(director);
            }
            Style style = db.Styles.Find(film.Style);
            ofOtherFilm = false;
            foreach (var f in db.Films)
            {
                if (f.Id != film.Id && style.Id == f.Style)
                {
                    ofOtherFilm = true;
                    break;
                }
            }
            if (!ofOtherFilm)
            {
                db.Styles.Remove(style);
            }
            db.SaveChanges();
        }

        



        //Refactor
        public static bool AddFilm(AddFilmWrapper afw)
        {
            Database db = new Database();
            Film film = AddFilmWrapperToFilm(afw);
            if (GetFilmIdByFilmDetails(film) != 0)
            {
                return false;
            }
            db.Films.Add(film);
            db.SaveChanges();
            int filmId=GetFilmIdByFilmDetails(film);
            AddActorsToNewFilmFromStringList(filmId, afw.Actors);
            return true;
        }
        public static void AddActorsToNewFilmFromStringList(int filmId, List<string> actorsS)
        {
            List<string> actors = new List<string>();
            foreach(var a in actorsS)
            {
                if (!actors.Contains(a)) { actors.Add(a); }
            }
            List<Actor> allActors = new List<Actor>();
            List<Actor> newActors = new List<Actor>();
            foreach (string a in actors)
            {
                if (GetActorByName(a) != null)
                {
                    allActors.Add(GetActorByName(a));
                }
                else
                {
                    Actor ac = new Actor();
                    ac.Name = a;
                    newActors.Add(ac);
                }
            }
            Database db = new Database();
            foreach(var a in newActors)
            {
                db.Actors.Add(a);
            }
            db.SaveChanges();
            foreach(var a in newActors)
            {
                allActors.Add(GetActorByName(a.Name));
            }
            foreach(Actor a in allActors)
            {
                ActorInFilm af = new ActorInFilm();
                af.FilmId = filmId;
                af.ActorId = a.Id;
                db.ActorInFilms.Add(af);
            }
            db.SaveChanges();
        }

        
        public static void RemoveAllActorsFromFilm(int filmId)
        {
            Database db = new Database();
            List<ActorInFilm> actorInFilms = new List<ActorInFilm>();
            foreach (var af in db.ActorInFilms)
            {
                if(af.FilmId == filmId)
                {
                    actorInFilms.Add(af);
                }
            }
            foreach(var af in actorInFilms)
            {
                db.ActorInFilms.Remove(af);
            }
            db.SaveChanges();
            Film film = db.Films.Find(filmId);
            DeleteFilmLeftBehinds(film);
            
        }

        public static bool ModifyFilm(AddFilmWrapper afw)
        {
            if (false)
            {
                return false;
            }
            Database db = new Database();
            db.Films.Find(afw.Id).Title = afw.Title;
            db.Films.Find(afw.Id).Year = afw.Year;


            if (GetStyleByName(afw.Style) == null)
            {
                Style style = new Style();
                style.Name = afw.Style;
                db.Styles.Add(style);
                db.SaveChanges();
            }
            db.Films.Find(afw.Id).Style = GetStyleByName(afw.Style).Id;
            if(GetDirectorByName(afw.Director) == null)
            {
                Director director = new Director();
                director.Name = afw.Director;
                db.Directors.Add(director);
                db.SaveChanges();
            }
            db.Films.Find(afw.Id).DirectorId = GetDirectorByName(afw.Director).Id;
            db.Films.Find(afw.Id).Added = afw.Added;
            db.Films.Find(afw.Id).Watched = afw.Watched;
            db.Films.Find(afw.Id).IsWatched = afw.IsWatched;

            RemoveAllActorsFromFilm(afw.Id);
            AddActorsToNewFilmFromStringList(afw.Id, afw.Actors);
            db.SaveChanges();
            return true;
        }


        public static Film AddFilmWrapperToFilm(AddFilmWrapper afw)
        {
            Database db = new Database();
            Film film = new Film();
            film.Title = afw.Title;
            film.UserId = afw.UserId;
            film.Year = afw.Year;
            if (GetStyleByName(afw.Style) == null)
            {
                Style style = new Style();
                style.Name = afw.Style;
                db.Styles.Add(style);
                db.SaveChanges();
            }
            film.Style = GetStyleByName(afw.Style).Id;
            if (GetDirectorByName(afw.Director) == null)
            {
                Director director = new Director();
                director.Name = afw.Director;
                db.Directors.Add(director);
                db.SaveChanges();
            }
            film.DirectorId = GetDirectorByName(afw.Director).Id;
            film.Added = afw.Added;
            film.Watched = afw.Watched;
            film.IsWatched = afw.IsWatched;

            //Here should be a method called to create the ActorInFilms too, yet it is somewhere else.

            return film;
        }
        internal static AddFilmWrapper FilmToAddFilmWrapper(Film film)
        {
            Database db = new Database();
            AddFilmWrapper afw = new AddFilmWrapper();
            afw.Id = film.Id;
            afw.UserId = film.UserId;
            afw.Title = film.Title;
            afw.Year = film.Year;
            afw.Style = db.Styles.Find(film.Style).Name;
            afw.Director = db.Directors.Find(film.DirectorId).Name;
            afw.Added = film.Added;
            afw.Watched = film.Watched;
            afw.IsWatched = film.IsWatched;
            afw.Actors = new List<string>();
            Database db2 = new Database();
            foreach (var af in db.ActorInFilms)
            {
                if (af.FilmId == film.Id)
                {
                    afw.Actors.Add(db2.Actors.Find(af.ActorId).Name);
                }
            }
            return afw;
        }





        /// <summary>
        /// GetFilmIdByFilmDetails returns the Id of a Film with identical details to the one given as an argument.
        /// Use this method to check whether a newly made film (that supposedly has an Id of 0 or null) already exists in the database, and if so, get its Id.
        /// The method returns 0 if the film is not found in the database.
        /// </summary>
        /// <param name="film"></param>
        /// <returns>Id if exists, 0 if does not exist</returns>
        public static int GetFilmIdByFilmDetails(Film film)
        {
            Database db = new Database();
            foreach (Film f in db.Films)
            {
                if (f.UserId == film.UserId && f.Title == film.Title && f.Year == film.Year && f.Style == film.Style && f.DirectorId == film.DirectorId)
                {
                    return f.Id;
                }
            }
            return 0;
        }
        public static Director GetDirectorByName(string name)
        {
            Database db = new Database();
            foreach (var d in db.Directors)
            {
                if (d.Name == name)
                {
                    return d;
                }
            }
            return null;
        }
        public static Style GetStyleByName(string name)
        {
            Database db = new Database();
            foreach (var s in db.Styles)
            {
                if (s.Name == name)
                {
                    return s;
                }
            }
            return null;
        }
        public static Actor GetActorByName(string name)
        {
            Database db = new Database();
            foreach (var a in db.Actors)
            {
                if (a.Name == name)
                {
                    return a;
                }
            }
            return null;
        }
        public static List<Actor> GetActorsListOfFilm(Film film)
        {
            Database db = new Database();
            List<Actor> actors = new List<Actor>();
            foreach (var a in db.Actors)
            {
                Database db2 = new Database();
                foreach (var af in db2.ActorInFilms)
                {
                    if (af.ActorId == a.Id && af.FilmId == film.Id)
                    {
                        actors.Add(a);
                    }
                }
            }
            return actors;
        }
    }
}
