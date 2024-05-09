using System;
using System.Collections.Generic;

#nullable disable

namespace MovieWatcher.Models
{
    public partial class ActorInFilm
    {
        public int Id { get; set; }
        public int ActorId { get; set; }
        public int FilmId { get; set; }
    }
}
