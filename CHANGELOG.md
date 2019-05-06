# Changelog
All notable changes to our site will be documented in this file.

Changes are grouped by version.

## 2.0.0 - 5/5/2019
### Added
- Added fuzzy search and suggested book recommendations from Cambridge Team's RPC.
- Added supplementary items like pens, pencils and notebooks based on client's recommendations.
- Added currently trending books based on client's recommendations.
- Added back button to allow for easier maneuvering between pages.
- Added Github Link.
- Added FAQ section in About Page.
- Added Documentation Page.

### Changed
- Updated CSS to be browser width responsive.
- Updated display to handle the supplementary items and to handle different map layouts.
- Changed the map to be responsive as well as allow pop-ups and different views.

### Removed
- Removed previous exact search from MVP.
- Removed old map code because initially hardcoded elements.

## 1.0.0 - 3/6/2019
### Added
- Added initial website framework containing initial search, results display page and details page for each book.
- The search queries the MYSQL database for exact author, title, and ASIN.
- The detail page displays more information about the book like title, author, asin, category as well as bookstores that carry the book and their locations.
- Results page contains exact matches based on search term and radio button selected.
- Added relevant CSS for the site.
- Added neccesary MYSQL queries and handle escape strings.

### Changed
- Initial mockups had descriptions for the book, but due to a lack of book descriptions in the data meant that this wasn't possible for 0.5.0.

### Removed
- Removed dark blank stock images and stock text.
- Removed auto-fill on search due to database wait times.
