Syntax examples

This is not an exhaustive listing of Markdown's syntax, and in many cases multiple styles of syntax are available to accomplish a particular effect. See the full Markdown syntax for more information. Characters which are ordinarily interpreted by Markdown as formatting commands will instead be interpreted literally if preceded by a backslash; for example, the sequence '\*' would output an asterisk rather than beginning a span of emphasized text. Markdown also does not transform any text within a "raw" block-level XHTML element; thus it is possible to include sections of XHTML within a Markdown source document by wrapping them in block-level XHTML tags.
[edit]Headings
HTML headings are produced by placing a number of hashes before the header text corresponding to the level of heading desired (HTML offers six levels of headings), like so:
# First-level heading

#### Fourth-level heading
The first two heading levels also have an alternative syntax:
First-level heading
===================

Second-level heading
--------------------
[edit]Paragraphs
A paragraph is one or more consecutive lines of text separated by one or more blank lines. Normal paragraphs should not be indented with spaces or tabs:
This is a paragraph. It has two sentences.

This is another paragraph. It also has 
two sentences.
[edit]Lists
* An item in a bulleted (unordered) list
    * A subitem, indented with 4 spaces
* Another item in a bulleted list
1. An item in an enumerated (ordered) list
2. Another item in an enumerated list
[edit]Line return
Line breaks inserted in the text are removed from the final result: the web browser is in charge of breaking lines depending on the available space. To force a line break, insert two spaces at the end of the line.
[edit]Emphasized text
*emphasis* or _emphasis_  (e.g., italics)
**strong emphasis** or __strong emphasis__ (e.g., boldface)
[edit]Code
To include code (formatted in monospace font), you can either surround inline code with backticks (`), like in
Some text with `some code` inside,
or indent several lines of code by at least four spaces, as in:
    line 1 of code
    line 2 of code
    line 3 of code
The latter option makes Markdown retain all whitespace—as opposed to the usual behaviour, which, by removing line breaks and excess spaces, would break indentation and code layout.
[edit]Line Breaks
When you do want to insert a break tag using Markdown, you end a line with two or more spaces, then type return. For example:
def show_results
tag_br space space
end
Result:
def show_results

end
[edit]Blockquotes
> "This entire paragraph of text will be enclosed in an HTML blockquote element.
Blockquote elements are reflowable. You may arbitrarily
wrap the text to your liking, and it will all be parsed
into a single blockquote element."
The above would translate into the following HTML:
<blockquote><p>This entire paragraph of text will be enclosed in an HTML blockquote element. Blockquote 
elements are reflowable. You may arbitrarily wrap the text to your liking, and it will all
be parsed into a single blockquote element.</p></blockquote>
[edit]Links
Links may be included inline:
[link text here](link.address.here)
Ex. [Markdown](http://en.wikipedia.com/wiki/Markdown)
Alternatively, links can be placed in footnotes outside of the paragraph, being referenced with some sort of reference tag. For example, including the following inline:
[link text here][linkref]
would produce a link if the following showed up outside of the paragraph (or at the end of the document):
[linkref]: link.address.here "link title here"
[edit]Horizontal rules
Horizontal rules are created by placing three or more hyphens, asterisks, or underscores on a line by themselves. You may use spaces between the hyphens or asterisks. Each of the following lines will produce a horizontal rule:
* * *
***
*****
- - -
---------------------------------------
[edit]Editors

While Markdown is a minimal markup language and is easily read and edited with a normal text editor there are special designed editors which preview the files direct with styles. There are a variety of such editors available for all of the major platforms.
[edit]Implementations

Given the popularity of Markdown, a variety of implementations are available for many different frameworks, platforms and languages. Notable sites such as GitHub, reddit and Stack Overflow use Markdown extensively to facilitate discussion between users.
[edit]External links

Official Markdown project at Daring Fireball