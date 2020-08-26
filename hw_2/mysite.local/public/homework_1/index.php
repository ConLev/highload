<?php

//1. Придумать класс, который описывает любую сущность из предметной области интернет-магазинов: продукт, ценник,
//посылка и т.п.

//2. Описать свойства класса из п.1 (состояние).

//3. Описать поведение класса из п.1 (методы).

/**
 * Class Product
 * @property string class
 */
class Clothes
{
    private $column1;
    private $column2;
    private $column3;
    private $column4;
    private $column5;
    private $id;
    private $brand;
    private $size;
    private $color;
    private $price;

    /**
     * Product constructor.
     * @param $id - артикул
     * @param $brand - производитель
     * @param $size - размер
     * @param $color - цвет
     * @param $price - цена
     */
    function __construct($id, $brand, $size, $color, $price)
    {
        $this->class = get_class($this);
        $this->column1 = 'Артикул';
        $this->column2 = 'Брэнд';
        $this->column3 = 'Размер';
        $this->column4 = 'Цвет';
        $this->column5 = 'Цена';
        $this->id = $id;
        $this->brand = $brand;
        $this->size = $size;
        $this->color = $color;
        $this->price = $price;
    }

    /**
     * метод для вывода товара
     */
    function view()
    {
        echo '<table style="border: 1px solid black; border-collapse: collapse">';
        echo '<thead>';
        echo '<tr>';
        echo "<td colspan='5' style='text-align: center; padding: 5px'>$this->class</td>";
        echo '</tr>';
        echo '<tr>';
        echo "<td style='border: 1px solid black; padding: 5px'>$this->column1</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>$this->column2</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>$this->column3</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>$this->column4</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>$this->column5</td>";
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo "<tr data-id='$this->id'>";
        echo "<td style='border: 1px solid black; padding: 5px'>$this->id</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>$this->brand</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>$this->size</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>$this->color</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>$ $this->price</td>";
        echo '</tr>';
        echo '</tbody>';
        echo '</table></br>';
    }

    function call()
    {
        $this->view();
    }
}

$product = new Clothes(1, 'Брэнд', 'XXL', 'red', 50);
$product->call();

//4. Придумать наследников класса из п.1. Чем они будут отличаться?

//Вы не можете менять ничего в классе родителе. Однако, можно изменить подход к описанию столбцов.
//Поля $id, $brand и тд. все равно придется оставить, но вместо полей $column1, $column2 и тд.
//можно сделать одно поле $columns, которое будет являться массивом и иметь вид:
//['id' => 'Артикул', 'brand' => 'Брэнд'] и тогда можно будет просто переопределить это поле в потомке.

class Shoes extends Clothes
{
    protected $column6;
    protected $column7;
    protected $shipping;
    protected $discount;

    function __construct($id, $brand, $size, $color, $price, $shipping, $discount)
    {
        parent::__construct($id, $brand, $size, $color, $price);
        $this->column6 = 'Доставка';
        $this->column7 = 'Скидка';
        $this->shipping = $shipping;
        $this->discount = $discount;
    }

    function view()
    {
        parent::view();
        echo '<table style="border: 1px solid black; border-collapse: collapse">';
        echo '<thead>';
        echo '<tr>';
        echo "<td colspan='2' style='text-align: center; padding: 5px'></td>";
        echo '</tr>';
        echo '<tr>';
        echo "<td style='border: 1px solid black; padding: 5px'>$this->column6</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>$this->column7</td>";
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo "<tr>";
        echo "<td style='border: 1px solid black; padding: 5px'>$this->shipping</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>$this->discount</td>";
        echo '</tr>';
        echo '</tbody>';
        echo '</table></br>';
    }
}

$shoes = new Shoes(2, 'Брэнд', '45', 'black', 100, 'free', '20%');
$shoes->call();

//5. Дан код:

echo '<a href="https://habr.com/ru/post/259627/">ключевое слово «static»</a></br>';
echo '<br>';

class A
{
    public function foo()
    {
        static $x = 0;
        echo ++$x;
    }
}

$a1 = new A();
$a2 = new A();
$a1->foo(); //1
$a2->foo(); //2
$a1->foo(); //3
$a2->foo(); //4

//Что он выведет на каждом шаге? Почему?
//Статическая локальная переменная. Присваивание выполняется при первом вызове,
//в отличие от локальной переменной значение сохраняется по окончании работы функции.
echo '</br>';

//Немного изменим п.5:

//6. Объясните результаты в этом случае.

class B
{
    public function foo()
    {
        static $x = 0;
        echo ++$x;
    }
}

class C extends B
{
}

$b1 = new B();
$c1 = new C();
$b1->foo(); //1
$c1->foo(); //1
$b1->foo(); //2
$c1->foo(); //2

echo '</br>';

//наследование класса приводит к тому, что создается новый метод
//в каждом методе в этом случае существует своя статическая локальная переменная

//7. *Дан код:

class D
{
    public function foo()
    {
        static $x = 0;
        echo ++$x;
    }
}

class E extends D
{
}

$d1 = new D; //В случае отсутствия аргументов, круглые скобки после названия класса можно опустить.
$e1 = new E;
$d1->foo(); //1
$e1->foo(); //1
$d1->foo(); //2
$e1->foo(); //2

//Что он выведет на каждом шаге? Почему?
//наследование класса приводит к тому, что создается новый метод
//в каждом методе в этом случае существует своя статическая локальная переменная