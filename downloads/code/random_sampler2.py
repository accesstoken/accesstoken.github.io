import random

def random_sampler(filename, k):
	sample = []
	with open(filename) as f:
		for n, line in enumerate(f):
			if n < k:
				sample.append(line.rstrip())
			else:
				r = random.randint(0, n)
				if r < k:
					sample[r] = line.rstrip()
	return sample
