# Laravel Has Tags
##### Performance centric model tags trait
[![Build Status](https://travis-ci.org/Priblo/Laravel-Has-Tags.svg?branch=master)](https://travis-ci.org/Priblo/Laravel-Has-Tags)


### Why another tagging trait?
At [Priblo](https://www.priblo.com) we couldn't find a suitable tagging trait for Laravel. Each one fell short for some reason or another. Mainly in the performance department.
Tags are an important part of Priblo and we needed to get them right. Opting for the [Decorator pattern](https://en.wikipedia.org/wiki/Decorator_pattern) we put an emphasis on caching and performance.
We considered contributing to other projects, but none were in line with our design philosophy. So here it is our implementation, hoping it will be useful to someone else.
