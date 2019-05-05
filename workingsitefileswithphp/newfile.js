var options = {
  shouldSort: true,
  tokenize: true,
  threshold: 0.6,
  location: 0,
  distance: 100,
  maxPatternLength: 32,
  minMatchCharLength: 1,
  keys: [
    "asin",
    "title",
    "author"
  ]
};
var books = '[{"asin":"000195850X","title":"Underground","author":David MACAULAY},{"asin":"000215949X","title":"Mexico The Beautiful Cookbook: Authentic Recipes from the Regions of Mexico","author":Susanna Palazuelos},{"asin":"000217068X","title":"The Marsh Arabs","author":Wilfred Thesiger},{"asin":"000255206X","title":"The Best of Thailand: A Cookbook","author":Grace Young},{"asin":"000255268X","title":"My Kenya Days","author":Wilfred Thesiger},{"asin":"000255710X","title":"The Danakil Diary: Journeys Through Abyssinia, 1930-34","author":Wilfred Thesiger},{"asin":"000414077X","title":"The Vegetarian Society's New Vegetarian Cookbook","author":Heather Thomas},{"asin":"000472514X","title":"SAS Secret War","author":Tony Jeapes},{"asin":"000638692X","title":"The Bronski House: A Return to the Borderlands","author":Philip Marsden},{"asin":"000638871X","title":"A History of Hong Kong","author":Frank Welsh},{"asin":"000712032X","title":"Rumi: Hidden Music","author":Maryam Mafi},{"asin":"000712693X","title":"Britain BC: Life in B]';
var fuse = new Fuse(books,options); // "list" is the item array
var result = fuse.search("David Macaly");
alert(result);
