using System.Collections.Generic;

#nullable disable

namespace MovieWatcher.Models
{
    public partial class MyMoviesViewModel
    {
        public List<FilmViewModel> filmWrapper { get; set; }
        public Searcher searcher { get; set; }
    }
}
