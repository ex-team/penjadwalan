<?php

/**
 * PSO Algorithm implementation
 * 
 * #############################################################################
 * #FUNCTION:               <Booth's function>
 * #EQUATION:               <f(x,y)=(x+2y-7)^2 + (2x+y-5)^2>
 * #MINIMUM:                <f(1,3)=0>
 * #SEARCH DOMAIN:          < -10<=x,y<=10 >
 * @link http://en.wikipedia.org/wiki/Test_functions_for_optimization the function link
 * #############################################################################
 * 
 * @author Ali MasudianPour <masud.amp@gmail.com>
 */
class Problem {
	
    /* @var MAX_ITERATION Problem the max iteration number */
    const MAX_ITERATION = 1000;
    /* @var DIMENSION Problem problem dimension */
    const DIMENSIONS = 2;
    /* @var MAX_VALUE Problem maximum value for x,y */
    const MAX_VALUE = 10;
    /* @var MAX_VALUE Problem maximum value for x,y */
    const MIN_VALUE = -10;

    /* @var $PARTICLE_NUMBER ClassName  number of parcicles */
    const PARTICLES_NUMBER = 30;

    /**
     * fitness function <Booth's function>
     * 
     * @param integer $x x
     * @param integer $y y
     * @return integer the function result
     */
    protected function fitnessFunction($x, $y) {
        return pow(($x + (2 * $y) - 7), 2) + pow(((2 * $x) + $y - 5), 2);
    }

    /**
     * to get answer
     * actually, f(1,3)=0
     * @return array [1]=x | [2]=y
     */
    protected function getAnswer() {
        return array(
            1 => 1,
            2 => 3
        );
    }

}

class Pso extends Problem {

    //between 0.9 and 1.2
    const INERTIAL_WEIGHT = 0.9;
    //acceleration constant
    const C1 = 2;
    //acceleration constant
    const C2 = 2;

    //swarm
    public $swarm = array();
    //Global Best
    public $gBest = array();
    //Best Fitness over all particles
    public $bestFitness = 999999999999;

    /**
     * to calculate volacity
     * @param integer $index particle index
     * @param integerr $dimention particle dimension
     * @return mixed result of calculation
     */
    private function calculateVelocity($index, $dimention) {
        //The euquation as shown in ORIGINAL paper
        return self::INERTIAL_WEIGHT * $this->swarm[$index]["VELOCITY"][$dimention] +
                (self::C1 * rand(0, 1) * ($this->swarm[$index]["P_BEST"]["POSITION"][$dimention] - $this->swarm[$index]["POSITION"][$dimention])) +
                (self::C2 * rand(0, 1) * ($this->gBest["POSITION"][$dimention] - $this->swarm[$index]["POSITION"][$dimention]));
    }

    /**
     * to calculate position
     * @param integer $index particle index
     * @param integerr $dimention particle dimension
     * @return mixed result of calculation
     */
    private function calculatePbest($index, $dimention) {
        //The euquation as shown in ORIGINAL paper
        return $this->swarm[$index]["POSITION"][$dimention] + $this->swarm[$index]["VELOCITY"][$dimention];
    }

    /**
     * to produce a random value between problem's MAX and MIN value
     * @return integer a value between problem's MAX and MIN value
     */
    private function generateRandomPosition() {
        return rand(Problem::MIN_VALUE, Problem::MAX_VALUE);
    }

    /**
     * to produce a random value for velocity
     * @return double a random value for velocity
     */
    private function generateRandomVelocity() {
        return rand(-(Problem::MAX_VALUE / 3), (Problem::MAX_VALUE / 3));
    }

    /**
     * Constructor function
     * all initializations take place in the following method
     */
    public function __construct() {
        //initialization
        for ($i = 1; $i <= Problem::PARTICLES_NUMBER; $i++) {
            for ($j = 1; $j <= Problem::DIMENSIONS; $j++) {

                $randomPosition = $this->generateRandomPosition();
                $randomVelocity = $this->generateRandomVelocity();

                //velocity
                $this->swarm[$i]["VELOCITY"][$j] = $randomVelocity;
                //position
                $this->swarm[$i]["POSITION"][$j] = $randomPosition;
            }

            //current particles
            $particle = $this->swarm[$i];
            //fitness calculation of currenr particle
            $fitness = $this->fitnessFunction($particle["POSITION"][1], $particle["POSITION"][2]);
            //if fitness of current particle was beter than global best fitness
            if ($fitness < $this->bestFitness) {
                //replacing best fitness and global best
                $this->bestFitness = $fitness;
                $this->gBest = $particle;
            }
            $this->swarm[$i]["P_BEST"] = $this->swarm[$i];
        }
    }

    /**
     * ALGORITHM MAIN METHOD
     * ALGORITHM EXECUTION
     */
    public function run() {

        $generation = 0;
        print "STARTED \n";
        while ($generation != Problem::MAX_ITERATION) {
            print "GENERATION[$generation]\t-----> BEST FITNESS = #{$this->bestFitness}# \t -- X={$this->gBest["POSITION"][1]}\tY={$this->gBest["POSITION"][2]} \n";
            //to make output more beautiful -- some sleep :)
            usleep(5000);
            // main loop through particles
            for ($i = 1; $i <= count($this->swarm); $i++) {
                
                    //position calculation
                    $this->swarm[$i]["POSITION"][$j] = $this->calculatePbest($i, $j) > Problem::MAX_VALUE ? Problem::MAX_VALUE : ($this->calculatePbest($i, $j) < Problem::MIN_VALUE ? Problem::MIN_VALUE : ($this->calculatePbest($i, $j)));
                    //velocity calculation
                    $this->swarm[$i]["VELOCITY"][$j] = $this->calculateVelocity($i, $j) > Problem::MAX_VALUE ? Problem::MAX_VALUE : ($this->calculateVelocity($i, $j) < Problem::MIN_VALUE ? Problem::MIN_VALUE : $this->calculateVelocity($i, $j));
                }

                //calculating fitness of current particle
                $fitness = $this->fitnessFunction($this->swarm[$i]["POSITION"][1], $this->swarm[$i]["POSITION"][2]);
                //if better fitness ...
                if ($fitness < $this->bestFitness) {
                    $this->bestFitness = $fitness;
                    $this->gBest = $this->swarm[$i];
                }
            }
            $generation++;
            //if found the best, exit takes place
            if ($this->gBest["POSITION"] == $this->getAnswer()) {
                print "WOW #GENERATION -{$generation}-#, FOUND THE BEST MINIMUM | X=[{$this->gBest["POSITION"][1]}] - Y=[{$this->gBest["POSITION"][2]}]";
                exit();
            }
        }
    }

}

//making new instance of Pso class
$test = new Pso();
//running the algorithm
$test->run();