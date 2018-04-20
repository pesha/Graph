<?php

 class SortNodeByName extends SplMaxHeap
  {
      function compare(Node $object1, Node $object2)
      {
          return $object1->getName() > $object2->getName() ? -1 : 1;
      }
  }