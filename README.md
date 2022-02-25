<h1 align="center">Joole Reflector</h1>
<br>
<b>Joole Reflector is used to work with the properties of objects, their changes and merges.</b>
<br>
<br>
<h2>Getting Started</h2>
<hr>

<p>Download it with composer:</p>
<code>composer require zloynick/joole-reflector</code>
<br>
<h2>Usage</h2>
<hr>
Init your reflector class:
<br>
<pre>
<code>
...
use joole\reflector\Reflector;
...
</code>
</pre>
<p>Build reflection object:</p>
<pre>
<code>
...
$reflectedClass = $reflector->buildFromObject($yourClass);
//OR
$reflectedClass = $reflector->buildFromObject(
    YourClass::class,// class as string
    [$constructorParams1, $constructorParams2],// Using for class constructor
);
...
</code>
</pre>
<p>Change private/protected properties:</p>
<pre>
<code>
...
$reflectedClass->getProperty('exampleProperty')->setValue($value);
// Notice: $value's type must be instance of property type
// or null if property nullable. "exampleProperty" is a property of
// your class, that had reflected.
...
</code>
</pre>
<p>Properties merging:</p>
<pre>
<code>
...
$reflector->merge($class, [
    'id' => $id,
    'value' => $value,
]);
// OR
$reflector->merge($class, $classFrom, [
    'id' => $id,
    'value',//if exists at $classFrom
]);
...
</code>
</pre>