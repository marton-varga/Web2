using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;

#nullable disable

namespace MovieWatcher.Models
{
    public partial class FilmViewModel
    {
        public int Id { get; set; }
        public string Title { get; set; }
        public int Year { get; set; }
        public string Style { get; set; }
        public string Director { get; set; }
        public List<string> Actors { get; set; }
        public DateTime Added { get; set; }
        public DateTime? Watched { get; set; }
        public bool IsWatched { get; set; }
    }
}
