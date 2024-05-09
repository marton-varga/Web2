using System;
using System.Collections.Generic;

#nullable disable

namespace MovieWatcher.Models
{
    public partial class Film
    {
        public int Id { get; set; }
        public int UserId { get; set; }
        public string Title { get; set; }
        public int Year { get; set; }
        public int Style { get; set; }
        public int DirectorId { get; set; }
        public DateTime Added { get; set; }
        public DateTime? Watched { get; set; }
        public bool IsWatched { get; set; }
    }
}
