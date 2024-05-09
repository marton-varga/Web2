using System;
using System.Collections.Generic;

#nullable disable

namespace MovieWatcher.Models
{
    public partial class StyleOfFilm
    {
        public int Id { get; set; }
        public int? FilmId { get; set; }
        public int? StyleId { get; set; }
    }
}
