# phytotaxa-cites-data

Citation data for Phytotaxa. See also the sister project on [Zootaxa](https://github.com/rdmpage/zootaxa-cites-data).

## Data

Data generated is available on FigShare [doi:10.6084/m9.figshare.15043698](https://doi.org/10.6084/m9.figshare.15043698)

## Harvesting Phytotaxa

### Step one: list of issues

Use `issues.php` Fetch each page from [Phytotaxa archives](https://www.mapress.com/j/pt/issue/archive) and save to ```html``` folder.

### Step two: extract contents pages

Use `extract-contents.php` to parse each file in ```html``` and extract URL for each issue, fetch HTML for table of contents and save in ```contents```.

### Step three:

Use `extract-articles.php` to parse each table of contents, extract URL for each article, and save that in ```articles```.

### Step four: 

Use `extract-references.php` to parse each article landing page and extract references as a text file, one reference per line, then run [anystyle](https://anystyle.io) to parse the references and output in CSL JSON.

### Step five:

Use `convert-references.php` to parse text and CSL and generate TSV file of references. This file is structured like this:

key | value
--|--
id | integer counter 1,2,…,n so that each reference string has unique id
guid | DOI for Phytotaxa article
guid-date | Publication date for Phytotaxa article
CSL fields | value for CSL field, e.g. `title`, `volume`, etc.
unstructured | original text of cited literature

Each row is a single work that is cited, the `guid` field groups together works cited by the same Phytotaxa article.

### Step six:

Use `export.php` to read TSV file and output RIS file.


## Data

Data is available in TSV and RIS formats.

### Example SQL queries (MySQL)

```
-- count by Phytotaxa article
SELECT count(DISTINCT guid) FROM cites;
SELECT count(DISTINCT guid) , SUBSTRING(`guid-date`, 1, 4) AS `year` FROM cites GROUP BY `year`;
SELECT count(distinct guid) , SUBSTRING(`guid-date`, 1, 4) AS `year` FROM cites WHERE `container-title` = "Phytotaxa" GROUP BY `year`;

-- count total literature cited
SELECT count(guid) FROM cites;
SELECT count(guid), SUBSTRING(`guid-date`, 1, 4) AS `year` FROM cites GROUP BY `year`;
SELECT count(guid) , SUBSTRING(`guid-date`, 1, 4) AS `year` FROM cites WHERE `container-title` = "Phytotaxa" GROUP BY `year`;

-- count literature cited by decade
SELECT SUBSTRING(issued,1,3) as decade, count(guid) FROM cites WHERE issued REGEXP '^[0-9]{4}$' GROUP BY decade;
```


## Anystyle

Use [Anystyle](https://anystyle.io) to parse the references. API doesn’t seem accessible over the web, so installed locally, then began a world of hurt. On my Mac:

```
sudo gem install anystyle-cli
ERROR:  Error installing anystyle-cli:
	bibtex-ruby requires Ruby version >= 2.4.0.
```

Hmmm:
```
ruby -v
ruby 2.3.7p456 (2018-03-28 revision 63024) [universal.x86_64-darwin18]
```

So, we need to update, see [Install Ruby on Your Mac: Everything You Need to Get Going](https://stackify.com/install-ruby-on-your-mac-everything-you-need-to-get-going/)

```
brew install ruby
```

Add the new ruby to our path, then load that into the current shell (using the ```source``` command):
```
If you need to have ruby first in your PATH run:
  echo 'export PATH="/usr/local/opt/ruby/bin:$PATH"' >> /Users/rpage/.bash_profile

source ~/.bash_profile
```

Now we can install ```anystyle``` … but it doesn’t work. We need to add gems to our path (see https://stackoverflow.com/a/14138490/9684):

```
echo 'export PATH="/usr/local/lib/ruby/gems/2.7.0/bin:$PATH"' >> /Users/rpage/.bash_profile

source ~/.bash_profile
```

Now it works! Pity there’s not a Docker version of ```anystyle``` so I could avoid all this :(
