# jquery-datatables-editable

This fork is meant to update the project to be used with modern versions of jQuery and Datatables. Dependencies are in 'bower.json'.

Steps To Build: (need [node/npm](https://nodejs.org/) installed)
```
git clone https://github.com/ansballard/jquery-datatables-editable.git editable
cd editable
npm update -g npm
npm install
npm install -g bower
bower install
grunt
```

To view the example page locally, run `grunt serve` in the project directory, and navigate to [localhost:8000](http://localhost:8000). To develop locally, use `grunt dev` to lint and minify on file changes.

Any formatting error should show up after running grunt, and a minified version/source map will be generated.

[Example using bootstrap](http://ansballard.github.io/jquery-datatables-editable/)
