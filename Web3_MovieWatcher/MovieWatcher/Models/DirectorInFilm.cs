using System;
using System.Collections.Generic;

#nullable disable

namespace MovieWatcher.Models
{
    public partial class DirectorInFilm
    {
        public int Id { get; set; }
        public int DirectorId { get; set; }
        public int FilmId { get; set; }
    }
}
